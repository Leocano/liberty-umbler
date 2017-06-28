<li><a href="abrir-chamado.php"><i class="fa fa-plus"></i>&nbsp;&nbsp;Abrir Chamado</a></li>
<?php 
	$user = $_SESSION['user'];
	$new_view = $user->getView();
	$id_view = $new_view->getIdView();
	if ($id_view == 2){
?>
<li><a href="gerenciar-relatorios-cliente.php"><i class="fa fa-file-o"></i>&nbsp;&nbsp;Relatórios</a></li>
<?php 
	}
?>