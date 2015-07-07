<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Portfolio website</title>

    <!-- Bootstrap core CSS -->
    <link href="template/css/bootstrap.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="include/ckeditor/ckeditor.js"></script>

    <!-- Custom styles for this template -->
    <link href="template/css/carousel.css" rel="stylesheet">
</head>
<!-- NAVBAR
================================================== -->
<body>
<div class="navbar-wrapper">
    <div class="container">

        <nav class="navbar navbar-inverse navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php?pageid=1">Portfolio</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php?pageid=1">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="index.php?pageid=8">Blogs</a></li>
                        <li><a href="index.php?pageid=7">Projecten</a></li>
                        <li class="dropdown">
                        <a href="index.php?pageid=1" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Admins <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="index.php?pageid=2">Admin users</a></li>
                                <li><a href="index.php?pageid=3">Admin blog</a></li>
                                <li><a href="index.php?pageid=6">Admin projecten</a></li>
                            </ul>
                        </li>






                        <!-- START BLOCK : ADMINMENU -->
                        <li class="dropdown">
                            <a href="index.php?pageid=1" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Admin <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="index.php?pageid=2">Admin Users</a></li>
                                <li><a href="index.php?pageid=5">Admin Projects</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Nav header</li>
                                <li><a href="#">Separated link</a></li>
                                <li><a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                        <!-- END BLOCK : ADMINMENU -->
                    </ul>
                    <!-- START BLOCK : LOGINTOP -->
                    <form class="navbar-form navbar-right" action="index.php?pageid=4" method="post">
                        <div class="form-group">
                            <input type="text" placeholder="Gebruikersnaam" class="form-control" name="gnaam">
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Wachtwoord" class="form-control" name="password">
                        </div>
                        <button type="submit" class="btn btn-success">Log in</button>
                    </form>
                    <!-- END BLOCK : LOGINTOP -->

                    <!-- START BLOCK : LOGGEDIN -->
                    <p class="navbar-text navbar-right">Ingelogd als <a href="#" class="navbar-link">{USERNAME}</a> - <a href="index.php?pageid=5">Logout</a></p>
                    <!-- END BLOCK : LOGGEDIN -->
                </div>
            </div>
        </nav>