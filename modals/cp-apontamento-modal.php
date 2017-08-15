<div class="modal fade" id="modal-cp-timekeeping">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">CP</h4>
            </div>
            <form name="form-cp-timekeeping">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Tipo</label>
                            <select class="form-control" >
                                <option value="Escritório">Escritório</option>
                                <option value="Cliente">Cliente</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Data</label>
                            <input class="datepicker date-input form-control" type="text" readonly name="" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Entrada</label>
                            <div class="input-group clockpicker">
                                <input type="text" class="form-control date-input" readonly value="08:00">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Saída</label>
                            <div class="input-group clockpicker">
                                <input type="text" class="form-control date-input" readonly value="17:00">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>