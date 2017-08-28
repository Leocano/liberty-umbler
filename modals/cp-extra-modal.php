<div class="modal fade" id="modal-cp-extra">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">CP</h4>
            </div>
            <form action="form-cp-extra" id="form-cp-extra">
                <div class="modal-body">
                    <input type="hidden" name="id-user" value="<?=$user->getIdUser()?>">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Tipo</label>
                            <select class="form-control" name="slt-type" >
                                <option value="Escritorio">Escritório</option>
                                <option value="Cliente">Cliente</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Data</label>
                            <input class="datepicker date-input form-control" type="text" readonly name="txt-date" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Início</label>
                            <div class="input-group clockpicker">
                                <input type="text" class="form-control date-input" name="txt-entry" readonly value="17:00">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Fim</label>
                            <div class="input-group clockpicker">
                                <input type="text" class="form-control date-input" name="txt-exit" readonly>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label>Justificativa</label>
                            <textarea class="form-control" name="txt-justification" id="txt-justification"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Fechar</button>
                    <button type="button" class="btn btn-success" id="btn-cp-extra"><i class="fa fa-check"></i>&nbsp; Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>