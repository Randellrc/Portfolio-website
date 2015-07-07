<?php
$content = new TemplatePower("template/files/admin_blog.tpl");
$content->prepare();

if(isset($_GET['action']))
{
    $action = $_GET['action'];
}else{
    $action = NULL;
}

if(isset($_SESSION['roleid'])){
    if($_SESSION['roleid'] == 2){
switch($action)
{
    case "toevoegen":
        if(!empty ($_POST['username'])
            && !empty($_POST['title'])
            && !empty($_POST['content'])){
            // insert


                // insert
                $insert_blog = $db->prepare("INSERT INTO blog SET
                  Username = :username,
                  Title = :title,
                  Content = :content,
                  Accounts_idAccounts = :accountid");
                $insert_blog->bindParam(":username", $_POST['username']);
                $insert_blog->bindParam(":title", $_POST['title']);
                $insert_blog->bindParam(":content", $_POST['content']);
                $insert_blog->bindValue(":accountid", 4);
                $insert_blog->execute();

                $userid = $db->lastInsertId();


        }else{
            // formulier
            $content->newBlock("BLOGFORM");
            $content->assign("ACTION", "index.php?pageid=3&action=toevoegen");
            $content->assign("BUTTON", "Toevoegen Blog");
        }
        break;
    case "wijzigen":
        if(isset($_POST['accountid']))
        {
            $update_blog = $db->prepare("UPDATE blog
                                          SET Title = :title,
                                              Content= :content
                                          WHERE  idBlog=:blogid");
            $update_blog->bindParam(":title", $_POST['title']);
            $update_blog->bindParam(":content", $_POST['content']);
            $update_blog->bindParam(":blogid", $_POST['blogid']);
            $update_blog->execute();
            $content ->newBlock("MELDING");
            $content->assign("MELDING", "blog is gewijzigd");



        }else{
            $get_blog = $db->prepare("SELECT blog.*, accounts.* FROM blog, accounts
                                      WHERE blog.idBlog = :blogid
                                      ");
            $get_blog->bindParam(":blogid", $_GET['blogid']);
            $get_blog->execute();
            $blog = $get_blog->fetch(PDO::FETCH_ASSOC);
            $content->newBlock("BLOGFORM");
            $content->assign(array(
                "TITEL" => $blog['Title'],
                "CONTENT" => $blog['Content'],
                "BLOGID" => $blog['idBlog'],
                "ACTION" => "index.php?pageid=3&action=wijzigen",
                "BUTTON" =>"Wijzigen Blog",
                "USERNAME" => $blog['Username']



            ));
        }
        break;
    case "verwijderen":
        if(isset($_GET['blogid']))
        {
            $check_blog = $db->prepare("SELECT count(*) FROM blog WHERE idBlog = :blogid");
            $check_blog->bindParam(":blogid", $_GET['blogid']);

            $check_blog->execute();

            if($check_blog->fetchColumn() == 1){
                $get_blog = $db->prepare("SELECT * FROM blog, accounts
                                      WHERE accounts.idAccounts = blog.Accounts_idAccounts
                                      AND blog.idBlog = :blogid");
                $get_blog->bindParam(":blogid", $_GET['blogid']);
                $get_blog->execute();
                $blog = $get_blog->fetch(PDO::FETCH_ASSOC);
                $content->newBlock("BLOGFORM");
                $content->assign(array(
                    "ACTION" =>  "index.php?pageid=3&action=verwijderen",
                    "BUTTON" => "Verwijderen Blog",
                    "TITEL" => $blog['Title'],
                    "CONTENT" => $blog['Content'],
                    "BLOGID" => $blog['idBlog'],
                    "USERNAME" => $blog['Username']


                ));
            }else{
                $errors->newBlock("ERRORS");
                $errors->assign("ERROR", "Deze gebruiker bestaat niet. Hoe ben je hier gekomen???");
            }
        }elseif(isset($_POST['blogid'])){
            // formulier verstuurd
            $delete = $db->prepare("DELETE FROM blog WHERE idBlog = :blogid");
            $delete->bindParam(":blogid", $_POST['blogid']);
            $delete->execute();
            $content->newBlock("MELDING");
            $content->assign("MELDING", "Blog is verwijderd");
        }else{
            $errors->newBlock("ERRORS");
            $errors->assign("ERROR", "Deze blog bestaat helemaal niet. Hoe ben je hier gekomen???");
        }
        break;
    default:
        // checken of er projecten zijn
        if(!empty($_POST['search'])){
            // heb ik resultaten met de search
            // check of ik resultaten heb
            try {
                $check_blog = $db->prepare("SELECT count(b.idBlog)
                                              FROM accounts a, blog b
                                              WHERE a.idAccounts = b.Accounts_idAccounts
                                              AND b.Title LIKE :zoek
                                              OR b.Content LIKE :zoek1
                                              ");
                $search = "%" . $_POST['search'] . "%";
                $check_blog->bindParam(":zoek", $search);
                $check_blog->bindParam(":zoek1", $search);
                $check_blog->execute();
            }catch(PDOException $error){
                $errors->newBlock("ERRORS");
                $errors->assign("ERROR", "Er gaat wat fout");
                break;
            }
            if($check_blog->fetchColumn() > 0){
                // nu heb ik resultaten
                $content->newBlock("BLOGLIST");
                $get_blog1 = $db->prepare("SELECT a.Username,
                                                      b.Title,
                                                      b.Content,
                                                      b.idBlog
                                              FROM accounts a, blog b
                                              WHERE a.idAccounts = b.Accounts_idAccounts
                                              AND  (b.Title LIKE :zoek
                                              OR b.Content LIKE :zoek1)
                                              ");
                $get_blog1->bindParam(":zoek", $search );
                $get_blog1->bindParam(":zoek1", $search);
                $get_blog1->execute();
                $content->newBlock("MELDING");
                $content->assign("MELDING", "Zoek criteria gevonden, tabel weergeven");
            }else{
                // melding laten zien, geen resultaten (geen tabel)
                $content->newBlock("MELDING");
                $content->assign("MELDING", "Geen blogs gevonden met de ingevulde criteria");
                break;
            }
        }else {
            // overzicht laten zien alles uit db
            $check_blogs = $db->query("SELECT count(b.idBlog)
                                              FROM accounts a, blog b
                                              WHERE a.idAccounts = b.Accounts_idAccounts");
            if ($check_blogs->fetchColumn() > 0) {
                // jaaaa, we hebben projecten
                $content->newBlock("BLOGLIST");
                $get_blog1 = $db->query("SELECT a.Username,
                                                      b.Title,
                                                      b.Content,
                                                      b.idBlog
                                              FROM accounts a, blog b
                                              WHERE a.idAccounts = b.Accounts_idAccounts");
            }else{
                $content->newBlock("MELDING");
                $content->assign("MELDING", "Geen blogs gevonden met de ingevulde criteria");
                break;
            }
        }
        while ($blogs = $get_blog1->fetch(PDO::FETCH_ASSOC)) {
            $content->newBlock("BLOGROW");
            $inhoud = $blogs['Content'];
            if (strlen($inhoud) > 30) {
                $inhoud = substr($blogs['Content'], 0, 30) . "...";
            }
            $content->assign(array(
                "USERNAME" => $blogs['Username'],
                "TITEL" => $blogs['Title'],
                "CONTENT" => $inhoud,
                "BLOGID" => $blogs['idBlog']
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