<div class="modal fade" id="modal-timekeep" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Novo Apontamento</h4>
			</div>
            <form name="form-timekeep" id="form-timekeep">
				<div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <label>Descrição *</label>
                            <textarea class="form-control" id="txt-desc" name="txt-desc" placeholder="Descreva as atividades realizadas"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id-ticket" id="id-ticket" value="<?=$id_ticket?>">
                    <input type="hidden" name="id-user" id="id-user" value="<?=$user->getIdUser()?>">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Fechar</button>
					<button type="button" class="btn btn-success" id="btn-timekeep"><i class="fa fa-check"></i>&nbsp; Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="modal-delete-timekeep" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Deletar Apontamento</h4>
			</div>
			<form name="form-delete-timekeep" id="form-delete-timekeep">
				<div class="modal-body">
					Deseja mesmo deletar este apontamento?
				</div>
				<div class="modal-footer">
					<input type="hidden" name="delete-timekeeping-id" id="delete-timekeeping-id">
					<input type="hidden" name="token">
					<input type="hidden" name="ticket-id" value="<?=$id_ticket?>">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Fechar</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="btn-confirm-delete-timekeep"><i class="fa fa-trash"></i>&nbsp; Excluir</button>
				</div>
			</form>
		</div>
	</div>
</div>




<div class="modal fade" id="modal-edit-timekeep" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Editar Apontamento</h4>
			</div>
			<form name="form-edit-timekeep" id="form-edit-timekeep">
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12">
							<label>Descrição *</label>
							<textarea class="form-control" id="txt-desc-edit" name="txt-desc" placeholder="Descreva as tarefas realizadas"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="id-user" id="id-user" value="<?=$user->getIdUser()?>">
					<input type="hidden" name="id-edit-timekeeping" id="id-edit-timekeeping">
					<input type="hidden" name="id_ticket" value="<?=$id_ticket?>">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Fechar</button>
					<button type="button" class="btn btn-success" id="btn-edit-timekeep-confirm"><i class="fa fa-check"></i>&nbsp; Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>