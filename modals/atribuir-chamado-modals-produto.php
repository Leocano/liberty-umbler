<div class="modal fade" id="modal-atribute" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Atribuir Chamado</h4>
			</div>
			<form name="form-atribute" id="form-atribute" action="p-atribuir-chamado.php" method="post">
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 form-group">
							<label>Principal</label>
							<?php 
								$dao = new ConsultantDAO;
								$consultants = $dao->getAllConsultants();

								if($main_consultant == null){
									$data_title = "data-title='Escolha um consultor'";
								}
							?>
							<select <?=$data_title?> class="selectpicker form-control" name="slt-main" id="slt-main" data-live-search="true">
								<?php 

								$checked = "";

								foreach ($consultants as $consultant) {
									// $blocked = "disabled";
									if ($main_consultant[0]->id_user == $consultant->id_user) {
										$checked = "selected";
									}

									// foreach ($timekeeping as $time) {
									// 	if ($time->id_user == $consultant->id_user){
									// 		$blocked = "";
									// 		break;
									// 	}
									// }

									?>
									<option value="<?=$consultant->id_user?>" <?=$checked?> ><?=$consultant->name?></option>
									<?php
									$checked = "";
								}
								?>
							</select>
						</div>
						<div class="col-xs-12 form-group">
							<?php 
							// var_dump($assigned);
							?>
							<label>Participantes</label>
							<select class="selectpicker form-control" name="slt-atribute[]" id="slt-atribute" multiple data-live-search="true" title="Escolha os consultores participantes">
								<?php 
								$checked = "";
								$i = 0;

								foreach ($consultants as $consultant) {
									foreach ($assigned as $assign) {
										if ($assign->id_user == $consultant->id_user){
											$checked = "selected";
											$i++;
										}
									}
									

									?>

									<option value="<?=$consultant->id_user?>" <?=$checked?> ><?=$consultant->name?></option>

									<?php
									$checked = "";
								}
								?>
							</select>
						</div>
						<div class="col-xs-12 form-group">
							<!-- <button class="btn btn-primary btn-sm" id="btn-all">
								<i class="fa fa-plus"></i> &nbsp;
								Adicionar todos
							</button> -->
							<button class="btn btn-danger btn-sm" id="btn-remove-all">
								<i class="fa fa-times"></i> &nbsp;
								Remover todos
							</button>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="id-ticket" id="id-ticket" value="<?=$id_ticket?>">
					<input type="hidden" name="priority" id="priority" value="<?=$priority?>">
					<input type="hidden" name="created-by" id="created-by" value="<?=$name?> (<?=$company?>)">
					<input type="hidden" name="category" id="category" value="<?=$category?>">
					<input type="hidden" name="subject" id="subject" value="<?=$subject?>">
					<input type="hidden" name="id-category" id="id-category" value="<?=$ticket[0]->id_category?>">
					<textarea class="hidden" name="description" id="description"><?=$desc_ticket?></textarea>
					<input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					<button type="button" class="btn btn-success" id="btn-atribute">Atribuir</button>
				</div>
			</form>
		</div>
	</div>
</div>