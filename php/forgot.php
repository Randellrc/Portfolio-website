<?php

include_once('include/function.php');

$content = new TemplatePower("template/files/forgot.tpl");
$content->prepare();

if(isset($_GET['action']))
{
    $action = $_GET['action'];
}else{
    $action = NULL;
}


switch($action){
    case "1":
        //username vergeten
        $check_account = $db->prepare("SELECT count(u.idUsers)
                                      FROM users u, accounts a
                                      WHERE u.Email = :email
                                            AND u.idUsers = a.Users_idUsers
                                      ");
        $check_account->bindParam(":email", $_POST['email']);
        $check_account->execute();

        if($check_account->fetchColumn() == 1){

            $get_account = $db->prepare("SELECT a.*, u.*
                                         FROM users u, accounts a
                                         WHERE u.Email = :email
                                              AND u.idUsers = a.Users_idUsers
                                         ");
            $get_account->bindParam(":email", $_POST['email']);
            $get_account->execute();

            $account = $get_account->fetch(PDO::FETCH_ASSOC);

            $Select_username = $db->prepare("SELECT username
                                            FROM accounts
                                            WHERE idAccounts = :accountid
                                            ");
            $Select_username->bindParam(":accountid", $account['idAccounts']);
            $Select_username->execute();

            $selected_username = $Select_username->fetch(PDO::FETCH_ASSOC);
            $selected = $selected_username['username'];

            //Email information
            $admin_email = "randellrc8@gmail.com";
            $email = $_POST['email'];
            $subject = "Gebruikersnaam vergeten";
            $comment = "Hier is je Gebruikersnaam: $selected";

            //send email
            mail($email, "$subject", $comment, "Van:" . $admin_email);

            //Email response
            $content->newBlock("MELDING");
            $content->assign("MELDING", "Er is een mail gestuurd naar je email");

        } else {
            $errors->newBlock("ERRORS");
            $errors->assign("ERROR", "Deze email bestaat niet");
        }

        break;
    case "2":
        // password vergeten
        $check_account = $db->prepare("SELECT count(u.idUsers)
                                               FROM users u, accounts a
                                               WHERE u.Email = :email
                                                     AND u.idUsers = a.Users_idUsers
                                               ");
        $check_account->bindParam(":email", $_POST['email']);
        $check_account->execute();

        if($check_account->fetchColumn() == 1){

            $get_account = $db->prepare("SELECT a.*, u.*
                                                FROM users u, accounts a
                                                WHERE u.Email = :email
                                                      AND u.idUsers = a.Users_idUsers
                                                ");
            $get_account->bindParam(":email", $_POST['email']);
            $get_account->execute();

            $account = $get_account->fetch(PDO::FETCH_ASSOC);

            $hash = hashgenerator();
            $insert_hash = $db->prepare("UPDATE accounts
                                                SET Reset = :hash
                                                WHERE idAccounts = :accountid ");
            $insert_hash->bindParam(":hash", $hash);
            $insert_hash->bindParam(":accountid", $account['idAccounts']);
            $insert_hash->execute();

            //Email information
            $admin_email = "randellrc8@gmail.com";
            $email = $_POST['email'];
            $subject = "Wachtwoord vergeten";
            $comment = "Hier is je code vul dit in:  127.0.0.1/project%203/index.php?pageid=9&action=4&code=$hash";

            //send email
            mail($email, "$subject", $comment, "Van:" . $admin_email);

            //Email response
            $content->newBlock("MELDING");
            $content->assign("MELDING", "Er is een mail gestuurd naar je email");

        } else {
            $errors->newBlock("ERRORS");
            $errors->assign("ERROR", "Deze email bestaat niet");
        }
        break;
    case "3":
        // passowrd en username vergeten
        $check_account = $db->prepare("SELECT count(u.idUsers)
                                      FROM users u, accounts a
                                      WHERE u.Email = :email
                                            AND u.idUsers = a.Users_idUsers
                                      ");
        $check_account->bindParam(":email", $_POST['email']);
        $check_account->execute();

        if($check_account->fetchColumn() == 1){
            // gebruiker gevonden
            $get_account = $db->prepare("SELECT a.*, u.*
                                        FROM users u, accounts a
                                        WHERE u.Email = :email
                                              AND u.idUsers = a.Users_idUsers
                                        ");
            $get_account->bindParam(":email", $_POST['email']);
            $get_account->execute();

            $account = $get_account->fetch(PDO::FETCH_ASSOC);

            $hash = hashgenerator();
            $insert_hash = $db->prepare("UPDATE accounts
                                        SET Reset = :hash
                                        WHERE idAccounts = :accountid
                                        ");
            $insert_hash->bindParam(":hash", $hash);
            $insert_hash->bindParam(":accountid", $account['idAccounts']);
            $insert_hash->execute();

            $Select_username = $db->prepare("SELECT username FROM accounts
                                            WHERE idAccounts = :accountid
                                            ");
            $Select_username->bindParam(":accountid", $account['idAccounts']);
            $Select_username->execute();

            $selected_username = $Select_username->fetch(PDO::FETCH_ASSOC);
            $selected = $selected_username['username'];

            //Email information
            $admin_email = "randellrc8@gmail.com";
            $email = $_POST['email'];
            $subject = "Wachtwoord en Gebruikersnaam vergeten";
            $comment = "Hier is je code vul dit in: 127.0.0.1/project%203/index.php?pageid=9&action=4&code=$hash
            en hier is je Gebruikersnaam: $selected";

            //send email
            mail($email, "$subject", $comment, "Van:" . $admin_email);

            //Email response
            $content->newBlock("MELDING");
            $content->assign("MELDING", "Er is een mail gestuurd naar je email");

        } else {
            $errors->newBlock("ERRORS");
            $errors->assign("ERROR", "Deze email bestaat niet");
        }
        break;
    case "4":
        //change password
        if(isset($_GET['code'])) {
            if (isset($_POST['password'])) {
                if ($_POST['password'] == $_POST['password1']) {

                    $get_account = $db->prepare("SELECT a.*, u.*
                                                FROM users u, accounts a
                                                WHERE a.Reset = :reset
                                                      AND u.idUsers = a.Users_idUsers
                                                ");
                    $get_account->bindParam(":reset", $_GET['code']);
                    $get_account->execute();

                    $account = $get_account->fetch(PDO::FETCH_ASSOC);
                    $update_password = $db->prepare("UPDATE accounts
                                                    SET Password = :password
                                                    WHERE idAccounts = :accountid
                                                    ");
                    $password = sha1($_POST['password']);
                    $update_password->bindParam(":password", $password);
                    $update_password->bindParam(":accountid", $account['idAccounts']);
                    $update_password->execute();

                    $content->newBlock("MELDING");
                    $content->assign("MELDING", "Wachtwoord veranderd");
                } else {
                    $content->newBlock("MELDING");
                    $content->assign("MELDING", "Wachtwoorden matchen niet");

                    $content->newBlock("VERANDEREN");
                    $content->assign("ACTION", "index.php?pageid=9&action=4&code=".$_GET['code']);
                }
            } else {
                $content->newBlock("VERANDEREN");
                $content->assign("ACTION", "index.php?pageid=9&action=4&code=".$_GET['code']);
            }
        }else {
           ($errors);
        }
        break;
    default:
        $content->newBlock("VERGETENFORM");
        $content->assign("ACTION", "index.php?pageid=9&action=".$_GET['option']);
}