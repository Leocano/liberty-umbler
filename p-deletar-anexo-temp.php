<?php 

require 'core/initializer.php';

$id = $_GET['id'];
$attachment = $_GET['attachment'];

var_dump($id);
var_dump($attachment);

unlink("temp/" . $id . "/" . $attachment);