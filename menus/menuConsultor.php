<!-- <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
        <i class='fa fa-ticket'></i>&nbsp;&nbsp;Chamados <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        <li><a href="gerenciar-chamados-consultor.php"><i class="fa fa-eye"></i>&nbsp;&nbsp;Gerenciar Chamados</a></li>
    </ul>
</li> -->

<?php
    // $user = $_SESSION['user'];
    // $dao = new UserDAO;
    // $id = $dao->getUserAreaId($user->getIdUser());

    // if ($id[0]->area_user == 5){
?>

<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
        <i class='fa fa-ticket'></i>&nbsp;&nbsp;Chamados <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        <!-- <li><a href="abrir-chamado-consultor.php"><i class="fa fa-plus"></i>&nbsp;&nbsp;Abrir Chamado</a></li> -->
        <li><a href="abrir-chamado-produtos.php"><i class="fa fa-plus"></i>&nbsp;&nbsp;Abrir Chamado - Produto</a></li>
    </ul>
</li>

<?php
// }
?>

<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
        <i class='fa fa-file'></i>&nbsp;&nbsp;Relatórios <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        <li><a href="criar-relatorio.php"><i class="fa fa-plus"></i>&nbsp;&nbsp;Criar Relatório</a></li>
        <li><a href="gerenciar-relatorios.php"><i class="fa fa-eye"></i>&nbsp;&nbsp;Gerenciar Relatórios</a></li>
    </ul>
</li>