<?php
$content = new TemplatePower("template/files/blogs.tpl");
$content->prepare();

if(isset($_GET['blogid'])){
    // controleren of alles er is

    $content->newBlock("DETAILS");
    $check_blog = $db->prepare("SELECT count(*) FROM blog
                                    WHERE idBlog = :blogid");
    $check_blog->bindParam(":blogid", $_GET['blogid']);
    $check_blog->execute();

    if($check_blog->fetchColumn() == 1){
        $get_blog = $db->prepare("SELECT b.*, a.Username FROM blog b, accounts a
                                    WHERE b.idBlog = :blogid
                                    AND b.Accounts_idAccounts = a.idAccounts");
        $get_blog->bindParam(":blogid", $_GET['blogid']);
        $get_blog->execute();
        $blog = $get_blog->fetch(PDO::FETCH_ASSOC);
        $content->assign(array("TITLE" => $blog['Title'],
            "CONTENT" => $blog['Content'],
            "USERNAME" => $blog['Username']));
    }else{
        // error
    }
}else{
    $check_blog = $db->query("SELECT count(*) FROM blog");

    if($check_blog->fetchColumn() > 0 ){
        $get_blog = $db->query("SELECT * FROM blog");
        $teller = 0;

        while($blogs = $get_blog->fetch(PDO::FETCH_ASSOC)){
            $teller++;
            if($teller % 3 == 1){
                // div openen
                $content->newBlock("BEGIN");
            }
            $blogcontent = substr($blogs['Content'], 0, 500)."...";
            $content->newBlock("BLOG");
            $content->assign(array
                ("TITLE" => $blogs['Title'],
                "CONTENT" => $blogcontent,
                "BLOGID" => $blogs['idBlog']));
            if($teller % 3 == 0){
                // div sluiten
                $content->newBlock("END");
            }
        }
    }else{
        // geen projecten gevonden
    }
}

if(isset($_SESSION['accountid'])){
if(isset($_GET['blogid'])){

if(!empty($_POST['comment'])){
    if(isset($_POST['blogid'])){
        $insert_comment = $db->prepare("INSERT INTO comments SET
                                        Text = :text,
                                        Blog_idBlog = :blogid,
                                        Accounts_idAccounts = :accountid");
        $insert_comment->bindParam(":text", $_POST['comment']);
        $insert_comment->bindParam(":blogid", $_POST['blogid']);
        $insert_comment->bindParam(":accountid", $_SESSION['accountid']);
        $insert_comment->execute();


    }else{
        $content->newBlock("COMMENTFORM");
        $content->assign(array(
            "BLOGID"=> $_GET['blogid'],
            "ACTION"=> "index.php?pageid=8"

        ));
    }}else{
    $content->newBlock("COMMENTFORM");
    $content->assign(array(
        "BLOGID"=> $_GET['blogid'],
         "ACTION"=> "index.php?pageid=8"));

}

$check_comment= $db->prepare("SELECT count(*) FROM comments WHERE
                              Blog_idBlog= :blogid
                                ");
$check_comment->bindParam(":blogid", $_GET['blogid']);
$check_comment->execute();

if($check_comment->fetchColumn() > 0){
    $get_blog = $db->prepare("SELECT a.Username, c.Text, c.idComments  FROM accounts a, comments c
                              WHERE Blog_idBlog = :blogid AND Accounts_idAccounts = idaccounts");
    $get_blog->bindParam(":blogid", $_GET['blogid']);
    $get_blog->execute();

    while($commentrow = $get_blog->fetch(PDO::FETCH_ASSOC)){
        $content->newBlock("COMMENTLIST");
        $content->assign(array
        ("TEXT" => $commentrow['Text'],
            "USERNAME"=> $commentrow['Username'],
            "COMMENTID"=> $commentrow['idComments']
        ));



    }


}else{
    $content->newBlock("MELDING");
    $content->assign("MELDING", "je hebt geen comments");
}

}

}