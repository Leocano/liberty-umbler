<div class="modal fade" id="modal-timekeep" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Apontar Horas</h4>
			</div>
			<form name="form-timekeep" id="form-timekeep">
				<div class="modal-body">
					<div class="row">
						<?php 
						$dao = new ConsultantDAO;
						$consultants = $dao->getUsersByTicket($id_ticket);
						if ($user->checkProfile(array(2, 3))){
							?>
							<div class="col-xs-12 form-group">
								<label>Representante *</label>
								<select class="selectpicker form-control" name="consultant-select" data-live-search="true" >
									<?php 
									foreach ($consultants as $consultant) {
									?>
										<option value="<?=$consultant->id_user?>"><?=$consultant->name?></option>
									<?php
									}
									?>
								</select>
							</div>
							<?php 
						}
						?>
						<div class="col-xs-12 col-md-6 form-group">
							<label>Tipo *</label>
							<select class="form-control" name="slt-type" id="slt-type" required>
								<option disabled selected value>Selecione o tipo do apontamento</option>
								<?php 
									$dao = new TimekeepingDAO;
									$timekeeping_types = $dao->getTimekeepingTypes();

									foreach ($timekeeping_types as $type) {
									?>
										<option value="<?=$type->id_timekeeping_type?>"><?=$type->desc_timekeeping_type?></option>
									<?php 
									}
								?>
							</select>
						</div>
						<div class="col-xs-12 col-md-6 form-group">
							<label>Data *</label>
							<input class="form-control date-input datepicker" type="text" name="txt-date" id="datepicker" readonly placeholder="Clique para alterar">
						</div>
						<div class="col-xs-6 col-md-3 form-group">
							<label>Horas *</label>
							<input class="form-control txt-time" type="text" value="00" name="txt-hours" data-mask="00">
						</div>
						<div class="col-xs-6 col-md-3 form-group">
							<label>Minutos *</label>
							<input class="form-control txt-time" type="text" value="00" name="txt-minutes" data-mask="00">
						</div>
						<div class="col-xs-12 col-md-6 form-group">
							<label>Custo</label>
							<input class="form-control" type="text" name="txt-cost" id="txt-cost" placeholder="Digite o custo (opcional)">
						</div>
						<div class="col-xs-12">
							<label>Descrição *</label>
							<textarea class="form-control" id="txt-desc" name="txt-desc" placeholder="Descreva as tarefas realizadas"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="id-ticket" id="id-ticket" value="<?=$id_ticket?>">
					<input type="hidden" name="id-user" id="id-user" value="<?=$user->getIdUser()?>">
					<input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>">
					<input type="hidden" name="status" id="status" value="<?=$new_id_status?>">
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
						<?php 
						if ($user->checkProfile(array(2, 3))){
							?>
							<div class="col-xs-12 form-group">
								<label>Representante *</label>
									<select class="selectpicker form-control edit-select" name="consultant-select" data-live-search="true" title="Escolha um Representante" >
										<?php 
										// $dao = new ConsultantDAO;
										// $consultants = $dao->getAllConsultants();

										foreach ($consultants as $consultant) {
										?>
											<option value="<?=$consultant->id_user?>"><?=$consultant->name?></option>
										<?php
										}
										?>
									</select>
							</div>
							<?php 
						}
						?>
						<div class="col-xs-12 col-md-6 form-group">
							<label>Tipo *</label>
							<select class="form-control" name="slt-type" id="slt-type-edit">
								<?php 
									$dao = new TimekeepingDAO;
									$timekeeping_types = $dao->getTimekeepingTypes();

									foreach ($timekeeping_types as $type) {
									?>
										<option value="<?=$type->id_timekeeping_type?>"><?=$type->desc_timekeeping_type?></option>
									<?php 
									}
								?>
							</select>
						</div>
						<div class="col-xs-12 col-md-6 form-group">
							<label>Data *</label>
							<input class="form-control date-input datepicker" type="text" name="txt-date" id="datepicker-id" readonly placeholder="Clique para alterar">
						</div>
						<div class="col-xs-6 col-md-3 form-group">
							<label>Horas *</label>
							<input class="form-control txt-time" type="text" name="txt-hours" id="txt-hours-edit" value="00" data-mask="00" data-mask-selectonfocus="true">
						</div>
						<div class="col-xs-6 col-md-3 form-group">
							<label>Minutos *</label>
							<input class="form-control txt-time" type="text" name="txt-minutes" id="txt-minutes-edit" value="00" data-mask="00" data-mask-selectonfocus="true">
						</div>
						<div class="col-xs-12 col-md-6 form-group">
							<label>Custo</label>
							<input class="form-control" type="text" name="txt-cost" id="txt-cost-edit" placeholder="Digite o custo (opcional)">
						</div>
						<div class="col-xs-12">
							<label>Descrição *</label>
							<textarea class="form-control" id="txt-desc-edit" name="txt-desc" placeholder="Descreva as tarefas realizadas"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="id-user" id="id-user" value="<?=$user->getIdUser()?>">
					<input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>">
					<input type="hidden" name="id-edit-timekeeping" id="id-edit-timekeeping">
					<input type="hidden" name="id_ticket" value="<?=$id_ticket?>">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Fechar</button>
					<button type="button" class="btn btn-success" id="btn-edit-timekeep-confirm"><i class="fa fa-check"></i>&nbsp; Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>