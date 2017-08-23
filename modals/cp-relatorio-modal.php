<div class="modal fade" id="modal-cp-report">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">CP</h4>
            </div>
            <form name="form-cp-report" id="form-cp-report" action="cp-folha-de-ponto.php" method="post">
                <div class="modal-body">
                    <?php
                        $dao = new ConsultantDAO;
                        $consultants = $dao->getAllConsultants();
                        if ($user->checkProfile(array(2, 3))){
                        ?>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label>Usuário</label>
                                    <select class="form-control selectpicker" name="id-user" data-live-search="true" data-title="Selecione um usuário" required>
                                    <?php 
                                        foreach ($consultants as $consultant) {
                                        ?>
                                            <option value="<?=$consultant->id_user?>"><?=$consultant->name?></option>
                                        <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        <?php
                        } else {
                            ?>
                            <input type="hidden" name="id-user" value="<?=$user->getIdUser()?>">
                            <?php
                        }
                    ?>
                    <div class="row">
                        <div class="col-xs-12 form-group month-input">
                            <label>Mês</label>
                            <input type="text" id="txt-month" name="txt-date" class="form-control monthpicker date-input" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Fechar</button>
                    <button type="submit" class="btn btn-success" id="btn-cp-report"><i class="fa fa-check"></i>&nbsp; Gerar</button>
                </div>
            </form>
        </div>
    </div>
</div>