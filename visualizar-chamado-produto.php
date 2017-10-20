<?php 

require 'headers/main-header.php';

$id = $_GET['id'];

$dao = new TicketDAO;
$ticket = $dao->getProductTicketById($id);

// Debug::kill($ticket);
?>

<div class="row">
    <div class="col-xs-12 title">
        <h1>
            ID
            <?=$ticket[0]->id_ticket?> - <?=$ticket[0]->subject_ticket?>
        </h1>
        <p class="help-block">
            Aberto em:
            <?=$ticket[0]->created?>
        </p>
    </div>
</div>

<ul class="nav nav-tabs">
<li><a data-toggle="tab" href="#details">Detalhes</a></li>
<?php
if (!$user->checkProfile(array(5))){
    if (isset($_GET['timekeeping']) && $_GET['timekeeping'] == "true"){
        $active = "active";
        $in = "in";
    }
?>
    <li class="<?=$active?>"><a data-toggle="tab" href="#timekeeping">Apontamentos</a></li>
    <?php
}
?>
    <li><a data-toggle="tab" href="#history">Histórico</a></li>
    <?php 
if (isset($_GET['solution']) && $_GET['solution'] == "true"){
    $solution_active = "active";
    $solution_in = "in";
}
?>
    <li><a class="<?=$solution_active?>" data-toggle="tab" href="#solucao">Solução</a></li>
</ul>

<?php
if (isset($_GET['timekeeping']) || isset($_GET['solution'])){
    echo "<div id='details' class='tab-pane fade'>";
} else {
    echo '<div id="details" class="tab-pane fade in active">';
}
?>
    <!-- <div id="details" class="tab-pane fade in active"> -->
    <?php 
        if (!$user->checkProfile(array(5))){
    ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="well well-lg">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="h4"><strong>Ações</strong></h2>
                    </div>
                    <div class="col-xs-12">
                        <?php 
                                $allowed_categories = array(1, 2, 4, 8);
                                if ((($solutionText == null || $solution_attachments == null) && !in_array($id_category, $allowed_categories) ) || $timekeeping == null){
                                    $no_close = "disabled";
                                    echo "<p class='help-block'>Para encerrar o chamado é necessário apontar, preencher a descrição da solução e anexar a documentação</p>";
                                } else {
                                    $no_close = "";
                                }
                                $json_ticket = json_encode($ticket);
                                $json_solution = json_encode($solutionText);

                                ?>
                        <button class="btn btn-default <?=$ticket[0]->disabled?> <?=$no_close?>" <?=$ticket[0]->disabled?> <?=$no_close?> href="p-fechar-chamado.php" id="btn-close-ticket">
                            <i class="fa fa-lock"></i>
                            &nbsp;Fechar
                        </button>
                        <form class="hidden" id="form-close-ticket">
                            <textarea class="hidden" name="j_ticket"><?=$json_ticket?></textarea>
                            <input type="hidden" name="status" value="2">
                            <textarea type="hidden" name="j_solution"><?=$json_solution?></textarea>
                            <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
                            <input type="text" name="disabled" value="<?=$no_close?>">
                        </form>
                        <?php
                                    $dao = new ConsultantDAO;
                                    $consultants = $dao->getUsersByTicket($id_ticket);
                                    $no_consultant == "";
                                    $is_assigned == false;

                                    foreach ($consultants as $cons) {
                                        if ($cons->id_user == $id_user){
                                            $is_assigned = true;
                                            break;
                                        }
                                    }
                                    
                                    if (($consultants == null || $is_assigned != true) && !$user->checkProfile(array(2,3))){
                                        $no_consultant = "disabled";
                                    }

                                    $no_assign = null;
                                    // if ($ticket[0]->id_category == 8){
                                    // 	$no_assign = "disabled";
                                    // }

                                    ?>
                            <button class="btn btn-default <?=$ticket[0]->disabled?>" <?=$ticket[0]->disabled?> <?=$no_consultant?> data-toggle="modal" data-target="#modal-timekeep">
                                <i class="fa fa-clock-o"></i>
                                &nbsp;Apontar Horas
                            </button>
                            <?php 
                            $new_dao = new UserDAO;
                            $area_id = $new_dao->getUserAreaId($id_user);
                            if ($user->checkProfile(array(3, 2)) || $area_id[0]->area_user == 2){
                                if ($user->checkProfile(array(3, 2))){
                            ?>
                            <a href="editar-chamado.php?id=<?=$id_ticket?>&token=<?=$_SESSION['token']?>" class="btn btn-default <?=$ticket[0]->disabled?>">
                                <i class="fa fa-pencil"></i>
                                &nbsp;Editar
                            </a>
                            <?php
                            }
                            ?>
                            <button class="btn btn-default <?=$ticket[0]->disabled?>" <?=$no_assign?> <?=$ticket[0]->disabled?> data-toggle="modal" data-target="#modal-atribute">
                                <i class="fa fa-user-plus"></i>
                                &nbsp;Atribuir
                            </button>
                            <?php 
                                if ($ticket[0]->disabled == "disabled" && $user->checkProfile(array(2, 3))){
                                    ?>
                            <a class="btn btn-default" href="p-reabrir-chamado.php?id=<?=$id_ticket?>&status=1&token=<?=$_SESSION['token']?>">
                                <i class="fa fa-unlock"></i>
                                &nbsp;Reabrir Chamado
                            </a>
                            <?php
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
    }
    ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="well well-lg">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <h2 class="h4"><strong>Status</strong></h2>
                        <?php
                            if ($user->checkProfile(array(1,2,3))){
                                $status_dao = new StatusDAO;
                                $new_status = $status_dao->getAllStatus();

                                if ($new_id_status == 2){
                                    $already_closed = "disabled";
                                }

                                ?>
                            <form name="form-status" id="form-status">
                                <input type="hidden" name="token" value="<?=$_SESSION['status']?>">
                                <input type="hidden" name="id-ticket" value="<?=$id_ticket?>">
                                <select id="slt-status" name="slt-status" class="form-control <?=$already_closed?>" <?=$already_closed?> <?=$no_consultant?>>
                                <?php
                                if ($new_id_status == 2){
                                    ?>
                                    <option value="2" selected class="disabled" disabled >Closed</option>
                                    <?php
                                } else {

                                    foreach ($new_status as $stat) {
                                        $selected = "";
                                        if($stat->id_status == $new_id_status){
                                            $selected = "selected";
                                        }
                                ?>
                                    <option value="<?=$stat->id_status?>" <?=$selected?> ><?=$stat->desc_status?></option>
                                <?php
                                    }
                                }
                                ?>
                                    </select>
                            </form>
                            <?php
                            } else {
                            ?>
                                <p>
                                    <?=$status?>
                                </p>
                                <?php
                            }
                        ?>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <h2 class="h4"><strong>Categoria</strong></h2>
                        <p>
                            <?=htmlentities($category)?>
                                <br>
                                <?php 
                            if ($desc_subcategory != null){
                                echo "(" . $desc_subcategory[0]->desc_subcategory . ")";
                            }
                            ?>
                        </p>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <h2 class="h4"><strong>Prioridade</strong></h2>
                        <p>
                            <span class="<?=$color?>"><i class="fa fa-exclamation-circle"></i>&nbsp; <?=$priority?></span>
                        </p>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <h2 class="h4"><strong>Módulo</strong></h2>
                        <p>
                            <?=$module?>
                        </p>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <h2 class="h4"><strong>Aberto por</strong></h2>
                        <p>
                            <a data-toggle="modal" data-target="#modal-customer" class="info-toggle info-toggle-customer" href="#" data-company="<?=$company?>"
                                data-name="<?=$name?>" data-email="<?=$ticket[0]->email?>" data-alt-email="<?=$ticket[0]->alternative_email?>" data-phone="<?=$ticket[0]->phone?>"
                                data-role="<?=$ticket[0]->role?>">
                                <?=$name?>
                            </a>
                            (
                            <a data-toggle="modal" data-target="#modal-company" class="info-toggle info-toggle-company" data-name="<?=$company?>"
                                data-address="<?=$ticket[0]->comp_address?>" data-bairro="<?=$ticket[0]->comp_bairro?>" data-city="<?=$ticket[0]->comp_city?>"
                                data-cep="<?=$ticket[0]->comp_cep?>" data-main-contact="<?=$ticket[0]->comp_main_contact?>" data-email="<?=$ticket[0]->comp_email?>"
                                data-phone="<?=$ticket[0]->comp_phone?>" data-cellphone="<?=$ticket[0]->comp_cellphone?>" href="#">
                                <?=$company?>
                            </a>)
                        </p>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <h2 class="h4"><strong>Atribuído a</strong></h2>
                        <p id="txt-assigned">
                            <?php 

                            // if ($ticket[0]->id_category == 8){
                            // 	echo "Todos";
                            // } else {
                                if ($main_consultant[0]->name == null){
                                    echo "Chamado não atribuído";
                                } else {
                                    echo "<strong>" . $main_consultant[0]->name . "</strong><br>";
                                }

                                foreach ($assigned as $assign) {
                                    echo $assign->name . "<br>";
                                }
                            // }								

                            ?>
                        </p>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <h2 class="h4"><strong>Centro de Custo</strong></h2>
                        <p>
                            <?=$cost_center?>
                        </p>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <h2 class="h4"><strong>Proposta</strong></h2>
                        <p>
                            <?=$proposal?>
                        </p>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <h2 class="h4"><strong>Email da Empresa</strong></h2>
                        <p>
                            <?=$comp_email?>
                        </p>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <h2 class="h4"><strong>Email do Cliente</strong></h2>
                        <p>
                            <?=$email?>
                        </p>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <h2 class="h4"><strong>Total de Horas</strong></h2>
                        <p>
                            <?php
                                echo $total_hours . 'h ' . $total_minutes . 'min';
                            ?>
                        </p>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <h2 class="h4"><strong>Número Externo</strong></h2>
                        <p>
                            <?=$external_number;?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="well well-lg">
                <div class="row">
                    <div class="col-xs-12 ticket-description">
                        <h2 class="h4"><strong>Descrição</strong></h2>
                        <div>
                            <?=$desc_ticket?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="well well-lg">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="h4"><strong>Anexos</strong></h2>

                        <?php 
                            $dao = new AttachmentDAO;
                            $attachments = $dao->getAttachmentsByTicketId($id_ticket);

                            if ($attachments == null){
                                ?>
                        <p>
                            Não existem anexos.
                        </p>
                        <?php 
                            } else {
                                echo "<div id='file-div'>";
                                foreach ($attachments as $attachment) {
                                    ?>
                        <i class="fa fa-paperclip"></i> &nbsp;<a href="<?=$attachment->path_attachment?>" download><?=$attachment->name_attachment?></a>
                        <br>
                        <?php
                                }
                                echo "</div>";
                            }

                            if ($user->checkProfile(array(5))){
                        ?>
                            <form name="form-editar-chamado" id="form-editar-chamado">
                                <input type="file" name="attachments[]" id="file-input" multiple>
                                <input type="hidden" name="id-ticket" value="<?=$id_ticket?>">
                            </form>
                            <!-- <p class="help-block">Insira todos os anexos de uma vez, segurando a tecla <kbd>Ctrl</kbd> e clicando sobre cada um</p> -->
                            <p class="help-block">Limite: 3MB</p>
                            <button class="btn btn-sm btn-primary" id="btn-anexar">
                                <i class="fa fa-plus"></i> &nbsp; Anexar
                            </button>

                            <?php 
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
