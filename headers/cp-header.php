<?php
require 'core/initializer.php';

$user = $_SESSION['user'];

if (!User::isLoggedIn()){
    Redirect::to("index.php");
}


$profile = $user->getProfile();
$id_profile = $profile->getIdProfile();

?>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <title>Liberty - QAS</title>
        <meta name="viewport" content="width=device-width" intitial-scale="1" maximum-scale="1">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" >
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Quicksand:300" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">
        <link href="assets/css/media-queries.css" rel="stylesheet">
    </head>
    <body class="main-body">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand index-title" href="home.php"><span class="glyphicon glyphicon-leaf"></span> Liberty QAS</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="home.php"><i class="fa fa-home"></i>&nbsp;&nbsp;Home</a></li>
                        <?php
                            // $menu = MenuFactory::factory($id_profile);
                            // $menu->buildMenu();
                        ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <?php 
                                    $user_name = explode(" ", $user->getName());
                                    echo "<i class='fa fa-cogs'></i>&nbsp;&nbsp;" . $user_name[0]; 
                                ?> 
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php 
                                if ($id_profile == 5){
                                    ?>
                                    <li><a href="alterar-senha.php"><i class="fa fa-key"></i>&nbsp;&nbsp;Alterar senha</a></li>
                                    <?php 
                                }
                                ?>
                                <li><a href="p-logout.php"><i class="fa fa-plug"></i>&nbsp;&nbsp;Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <div class="container content">