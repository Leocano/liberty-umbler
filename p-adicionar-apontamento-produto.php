<?php 

// Arquivo de processamento


require 'core/initializer.php';

if ($_POST['status'] == 2){
	exit();
}

$id_user = $_POST['id-user'];
$id_ticket = $_POST['id-ticket'];
$description = $_POST['txt-desc'];
$date = $_POST['txt-date'];

$date = str_replace("/","-",$date);
$date = strtotime($date);
$date = date('Y-m-d', $date);

$date = $date . " 00:00:00";

if (Validator::isEmpty(array($id_ticket, $description))){
	echo "Preencha todos os campos";
	exit;
}

$description = str_replace('"', "&quot;", $description);
$description = str_replace("'", "&quot;", $description);

$db->query(
    "
    INSERT INTO
        tb_product_timekeeping
    VALUES
    (
        null
    ,   ?
    ,   ?
    ,   ?
    ,   ?
    )
    ",
    array(
        $id_ticket
    ,   $id_user
    ,   $description
    ,   $date
    )
);

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistoryProduct($id_ticket, $id_user, "Apontamento de horas realizado por");

echo "success";