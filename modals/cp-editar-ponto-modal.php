<div class="modal fade" id="modal-cp-edit">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">CP</h4>
        </div>
        <form name="form-cp-edit" id="form-cp-edit">
            <input type="hidden" id="txt-id-cp" name="txt-id-cp">
            <div class="modal-body">
                <?php
                $dao = new ConsultantDAO;
                $consultants = $dao->getAllConsultants();
                if ($user->checkProfile(array(2, 3))){
                ?>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label>Usuário</label>
                            <select class="form-control selectpicker" id="slt-user-edit" name="id-user" data-live-search="true" data-title="Selecione um usuário">
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
                    <div class="col-md-6 form-group">
                        <label>Tipo</label>
                        <select class="form-control" id="slt-type-edit" name="slt-type" >
                            <option value="Escritorio">Escritório</option>
                            <option value="Cliente">Cliente</option>
                        </select>
                    </div>
                    <?php
                    if ($user->checkProfile(array(2,3))){
                    ?>
                    <div class="col-md-6 form-group">
                        <label>Data</label>
                        <input class="datepicker date-input form-control" id="txt-date-edit" type="text" readonly name="txt-date" value="">
                    </div>
                    <?php 
                    } else {
                        ?>
                        <input type="hidden" id="txt-date-edit" name="txt-date" value="<?=Date("d/m/Y")?>">
                        <?php
                    }
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Entrada</label>
                        <div class="input-group clockpicker">
                            <input type="text" id="txt-entry-edit" class="form-control date-input" name="txt-entry" readonly>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Saída</label>
                        <div class="input-group clockpicker">
                            <input type="text" id="txt-exit-edit" class="form-control date-input" name="txt-exit" readonly>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Início do almoço</label>
                        <div class="input-group clockpicker">
                            <input type="text" id="txt-break-start-edit" class="form-control date-input" name="txt-break-start" readonly>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Fim do almoço</label>
                        <div class="input-group clockpicker">
                            <input type="text" id="txt-break-end-edit" class="form-control date-input" name="txt-break-finish" readonly>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Fechar</button>
                <button type="button" class="btn btn-success" id="btn-cp-edit-submit"><i class="fa fa-check"></i>&nbsp; Salvar</button>
            </div>
        </form>
    </div>
</div>
</div>