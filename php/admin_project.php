<?php
$content = new TemplatePower("template/files/admin_project.tpl");
$content->prepare();
if(isset($_GET['action']))
{
    $action = $_GET['action'];
}else{
    $action = NULL;
}
if(isset($_SESSION['roleid'])){
    if($_SESSION['roleid'] == 2){
        switch($action) {
            case "toevoegen":
                if (!empty($_POST['title']) AND !empty($_POST['content'])) { // voorwaarde => insert
                    $insert = $db->prepare("INSERT INTO products
                                    SET Title = :title,
                                    Content = :content,
                                    Accounts_idAccounts = :account");
                    $insert->bindParam(":title", $_POST['title']);
                    $insert->bindParam(":content", $_POST['content']);
                    $insert->bindParam(":account", $_SESSION['accountid']);
                    $insert->execute();
                } else {
                    // formulier
                    $content->newBlock("PROJECTFORM");
                    $content->assign(array("ACTION" => "index.php?pageid=6&action=toevoegen",
                        "BUTTON" => "Toevoegen Project"));
                }
                break;
            case "wijzigen":
                if (isset($_GET['projectid'])) {
                    // ophalen project
                    $check_project = $db->prepare("SELECT count(*) FROM
                                                    accounts a, products p
                                                    WHERE a.idAccounts = p.Accounts_idAccounts
                                                    AND p.idProducts = :projectid");
                    $check_project->bindParam(":projectid", $_GET['projectid']);
                    $check_project->execute();
                    if ($check_project->fetchColumn() == 1) {
                        // hij bestaat in db
                        $get_project = $db->prepare("SELECT * FROM
                                                    accounts a, products p
                                                    WHERE a.idAccounts = p.Accounts_idAccounts
                                                    AND p.idProducts = :projectid");
                        $get_project->bindParam(":projectid", $_GET['projectid']);
                        $get_project->execute();
                        $project = $get_project->fetch(PDO::FETCH_ASSOC);
                        $content->newBlock("PROJECTFORM");
                        $content->assign(array(
                            "TITLE" => $project['Title'],
                            "CONTENT" => $project['Content'],
                            "PROJECTID" => $project['idProducts'],
                            "ACTION" => "index.php?pageid=6&action=wijzigen",
                            "BUTTON" => "Wijzigen Project"
                        ));
                    } else {
                        $errors->newBlock("ERRORS");
                        $errors->assign("ERROR", "Waarom heb je het projectid in de url veranderd???");
                    }
                } elseif (!empty($_POST['title'])
                    AND !empty($_POST['content'])
                    AND !empty($_POST['projectid'])
                ) {
                    $update = $db->prepare("UPDATE products SET Title = :title,
                                                  Content = :content
                                                  WHERE idProducts = :projectid");
                    $update->bindParam(":title", $_POST['title']);
                    $update->bindParam(":content", $_POST['content']);
                    $update->bindParam(":projectid", $_POST['projectid']);
                    $update->execute();
                    $content->newBlock("MELDING");
                    $content->assign("MELDING", "Project gewijzigd");
                } else {
                    $errors->newBlock("ERRORS");
                    $errors->assign("ERROR", "WTF doe je hier");
                }
                break;
            case "verwijderen":
                if(isset($_GET['projectid'])){
                    // formulier laten zien
                    $check_project = $db->prepare("SELECT count(*) FROM products
                                WHERE idProducts = :productid");
                    $check_project->bindParam(":productid", $_GET['projectid']);
                    $check_project->execute();
                    // check of er 1 rij is
                    if($check_project->fetchColumn() == 1){
                        // hij bestaat
                        // nu eerst gegevens ophalen
                        $get_project = $db->prepare("SELECT * FROM products
                                WHERE idProducts = :productid");
                        $get_project->bindParam(":productid", $_GET['projectid']);
                        $get_project->execute();
                        $project = $get_project->fetch(PDO::FETCH_ASSOC);
                        $content->newBlock("PROJECTFORM");
                        $content->assign(array(
                            "TITLE" => $project['Title'],
                            "CONTENT" => $project['Content'],
                            "PROJECTID" => $project['idProducts'],
                            "ACTION" => "index.php?pageid=6&action=verwijderen",
                            "BUTTON" => "Verwijder project"
                        ));
                    }else
                    {
                        $errors->newBlock("ERRORS");
                        $errors->assign("ERROR", "Item bestaat niet");
                    }
                }elseif(isset($_POST['projectid'])){
                    // item verwijderen
                }else{
                    // ERROR !!
                    $errors->newBlock("ERRORS");
                    $errors->assign("ERROR", "WTF doe je hier");
                }
                break;
            default:
                // checken of er projecten zijn
                if(!empty($_POST['search'])){
                    // heb ik resultaten met de search
                    // check of ik resultaten heb
                    try {
                        $check_project = $db->prepare("SELECT count(p.idProducts)
                                              FROM accounts a, products p
                                              WHERE a.idAccounts = p.Accounts_idAccounts
                                              AND p.Title LIKE :zoek
                                              OR p.Content LIKE :zoek1
                                              ");
                        $search = "%" . $_POST['search'] . "%";
                        $check_project->bindParam(":zoek", $search);
                        $check_project->bindParam(":zoek1", $search);
                        $check_project->execute();
                    }catch(PDOException $error){
                        $errors->newBlock("ERRORS");
                        $errors->assign("ERROR", "Er gaat wat fout");
                        break;
                    }
                    if($check_project->fetchColumn() > 0){
                        // nu heb ik resultaten
                        $content->newBlock("PROJECTLIST");
                        $get_projects = $db->prepare("SELECT a.Username,
                                                      p.Title,
                                                      p.Content,
                                                      p.idProducts
                                              FROM accounts a, products p
                                              WHERE a.idAccounts = p.Accounts_idAccounts
                                              AND  (p.Title LIKE :zoek
                                              OR p.Content LIKE :zoek1)
                                              ");
                        $get_projects->bindParam(":zoek", $search );
                        $get_projects->bindParam(":zoek1", $search);
                        $get_projects->execute();
                        $content->newBlock("MELDING");
                        $content->assign("MELDING", "Zoek criteria gevonden, tabel weergeven");
                    }else{
                        // melding laten zien, geen resultaten (geen tabel)
                        $content->newBlock("MELDING");
                        $content->assign("MELDING", "Geen projecten gevonden met de ingevulde criteria");
                        break;
                    }
                }else {
                    // overzicht laten zien alles uit db
                    $check_projects = $db->query("SELECT count(p.idProducts)
                                              FROM accounts a, products p
                                              WHERE a.idAccounts = p.Accounts_idAccounts");
                    if ($check_projects->fetchColumn() > 0) {
                        // jaaaa, we hebben projecten
                        $content->newBlock("PROJECTLIST");
                        $get_projects = $db->query("SELECT a.Username,
                                                      p.Title,
                                                      p.Content,
                                                      p.idProducts
                                              FROM accounts a, products p
                                              WHERE a.idAccounts = p.Accounts_idAccounts");
                    }else{
                        $content->newBlock("MELDING");
                        $content->assign("MELDING", "Geen projecten gevonden met de ingevulde criteria");
                        break;
                    }
                }
                while ($projects = $get_projects->fetch(PDO::FETCH_ASSOC)) {
                    $content->newBlock("PROJECTROW");
                    $inhoud = $projects['Content'];
                    if (strlen($inhoud) > 30) {
                        $inhoud = substr($projects['Content'], 0, 30) . "...";
                    }
                    $content->assign(array(
                        "USERNAME" => $projects['Username'],
                        "TITLE" => $projects['Title'],
                        "CONTENT" => $inhoud,
                        "PROJECTID" => $projects['idProducts']
                    ));
                }
        }
    }else{
        // je hebt niet de goede rechten
        $errors->newBlock("ERRORS");
        $errors->assign("ERROR", "Je hebt niet de goede rechten");
    }
}else{
    // je bent niet ingelogd
    $errors->newBlock("ERRORS");
    $errors->assign("ERROR", "Je bent niet ingelogd");
}