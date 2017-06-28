<?php 
require 'core/initializer.php';

if(User::isLoggedIn()){
	Redirect::to("home.php");
}

Token::generateToken();

?>

<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>Liberty - QAS</title>
	<meta name="viewport" content="width=device-width" intitial-scale="1" maximum-scale="1">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" >
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Quicksand:300" rel="stylesheet">
	<!-- <link href="assets/fonts/css/fontello-codes.css" rel="stylesheet"> -->
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/media-queries.css" rel="stylesheet">
</head>
<body class="index-body">