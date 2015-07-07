<?php
$content = new TemplatePower("template/files/admin_comment.tpl");
$content->prepare();

if(isset($_GET['action']))
{
    $action = $_GET['action'];
}else{
    $action = NULL;
}
switch($action)
{
    case "toevoegen":
        if(!empty ($_POST['username'])
            && !empty($_POST['Text'])){
            // insert


            // insert
            $insert_comment = $db->prepare("INSERT INTO comments SET
                  Username = :username,
                  Text = :text,
                  Accounts_idAccounts = :accountid");
            $insert_comment->bindParam(":username", $_POST['username']);
            $insert_comment->bindParam(":text", $_POST['Text']);
            $insert_comment->bindValue(":accountid", 4);
            $insert_comment->execute();

            $userid = $db->lastInsertId();


        }else{
            // formulier
            $content->newBlock("COMMENTFORM");
            $content->assign("ACTION", "index.php?pageid=10&action=toevoegen");
            $content->assign("BUTTON", "Toevoegen Comment");
        }
        break;
    case "wijzigen":

        if(isset($_POST['accountid']))
        {
            $update_comment = $db->prepare("UPDATE comments
                                          SET Text = :text
                                          WHERE  idComments=:commentid");
            $update_comment->bindParam(":text", $_POST['Text']);
            $update_comment->bindParam(":commentid", $_POST['commentid']);
            $update_comment->execute();
            $content ->newBlock("MELDING");
            $content->assign("MELDING", "comment is gewijzigd");



        }else{
            $get_comment = $db->prepare("SELECT comments.*, accounts.* FROM comments, accounts
                                      WHERE idComments = :commentid
                                      ");
            $get_comment->bindParam(":commentid", $_GET['commentid']);
            $get_comment->execute();
            $comment = $get_comment->fetch(PDO::FETCH_ASSOC);
            $content->newBlock("COMMENTFORM");
            $content->assign(array(
                "TEXT" => $comment['Text'],
                "COMMENTID" => $comment['idComments'],
                "ACTION" => "index.php?pageid=10&action=wijzigen",
                "BUTTON" =>"Wijzigen Comment",
                "USERNAME" => $comment['Username']



            ));
        }
        break;
    case "verwijderen":
        if(isset($_GET['commentid']))
        {
            $check_comment = $db->prepare("SELECT count(*) FROM comments WHERE idComments = :commentid");
            $check_comment->bindParam(":commentid", $_GET['commentid']);

            $check_comment->execute();

            if($check_comment->fetchColumn() == 1){
                $get_comment = $db->prepare("SELECT * FROM comments, accounts
                                      WHERE accounts.idAccounts = comments.Accounts_idAccounts
                                      AND comments.idComments = :commentid");
                $get_comment->bindParam(":commentid", $_GET['commentid']);
                $get_comment->execute();
                $comment = $get_comment->fetch(PDO::FETCH_ASSOC);
                $content->newBlock("COMMENTFORM");
                $content->assign(array(
                    "ACTION" =>  "index.php?pageid=10&action=verwijderen",
                    "BUTTON" => "Verwijderen Comment",
                    "TEXT" => $comment['Text'],
                    "COMMENTID" => $comment['idComments'],
                    "USERNAME" => $comment['Username']


                ));
            }else{
                $errors->newBlock("ERRORS");
                $errors->assign("ERROR", "Deze gebruiker bestaat niet. Hoe ben je hier gekomen???");
            }
        }elseif(isset($_POST['commentid'])){
            // formulier verstuurd
            $delete = $db->prepare("DELETE FROM comments WHERE idComments = :commentid");
            $delete->bindParam(":commentid", $_POST['commentid']);
            $delete->execute();
            $content->newBlock("MELDING");
            $content->assign("MELDING", "Comment is verwijderd");
        }else{
            $errors->newBlock("ERRORS");
            $errors->assign("ERROR", "Deze comment bestaat helemaal niet. Hoe ben je hier gekomen???");
        }
        break;
    default:
        $content->newBlock("COMMENTLIST");
        if(!empty($_POST['search'])){
            $get_comments = $db->prepare("SELECT comments.idComments,
                                    comments.Text,
                                    accounts.Username,
                                    accounts.idAccounts
                                    FROM comments, accounts
                                  WHERE comments.idComments = accounts.
                                  AND (accounts.Username LIKE :search
                                  OR users.Email LIKE :search2
                                  OR users.Surename LIKE :search3
                                  OR users.Name LIKE :search4)
                                     ");
            $search = "%".$_POST['search']."%";
            $get_comments->bindParam(":search", $search);
            $get_comments->bindParam(":search2", $search);
            $get_comments->bindParam(":search3", $search);
            $get_comments->bindParam(":search4", $search);
            $get_comments->execute();
            $content->assign("SEARCH", $_POST['search']);
        }else{
            $get_comments = $db->query("SELECT users.Surename,
                                    users.Name,
                                    users.Email,
                                    accounts.Username,
                                    accounts.idAccounts
                                  FROM users, accounts
                                  WHERE users.idUsers = accounts.Users_idUsers");
        }

        $get_comments = $db->query("SELECT comments.Text, comments.Accounts_idAccounts, comments.idComments, accounts. idAccounts, accounts. Username
                                    FROM comments, accounts
                                    WHERE comments. Accounts_idAccounts = accounts. idAccounts ");

        while($comment2 = $get_comments->fetch(PDO::FETCH_ASSOC)){
            $content->newBlock("COMMENTROW");



            $content->assign(array(
                "TEXT" => $comment2['Text'],
                "COMMENTID" => $comment2['idComments'],
                "USERNAME" => $comment2['Username']

            ));
        }
}