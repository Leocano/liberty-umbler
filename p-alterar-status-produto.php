<?php 

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$status = $_POST['slt-status'];
$id = $_POST['id-ticket'];

// $dao = new TicketDAO;

// $dao->changeStatus($id, $status);

if ($status == 2) {
    $db->query("UPDATE
                    tb_product_tickets
                SET
                    id_status = ?
                ,	date_closed = CURRENT_TIMESTAMP
                WHERE 
                    id_ticket = ?"
                ,
                array($status, $id)
                );
} else {
    $db->query("UPDATE
                tb_product_tickets
            SET
                id_status = ?
            WHERE 
                id_ticket = ?"
            ,
            array($status, $id)
            );
}

// include 'mail/mail-fechar-chamado.php';

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistoryProduct($id, $id_user, "Status do chamado alterado por");

// Redirect::to("visualizar-chamado.php?id=" . $id);