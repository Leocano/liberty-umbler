<div class="modal fade" id="modal-solution" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Adicionar texto à solução</h4>
			</div>
			<form name="form-solution" id="form-solution">
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 form-group">
							<label>Descrição</label>
							<textarea id="text-mce" name="txt-desc-solution"><?=$solutionText[0]->desc_solution?></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="id-ticket" id="id-ticket" value="<?=$id_ticket?>">
					<input type="hidden" name="id-user" id="id-user" value="<?=$user->getIdUser()?>">
					<input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Fechar</button>
					<button type="button" class="btn btn-success" data-dismiss="modal" id="btn-solution"><i class="fa fa-check"></i>&nbsp; Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>








<div class="modal fade" id="modal-solution-attachments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Adicionar anexos à solução</h4>
			</div>
			<form name="form-solution-attachments" id="form-solution-attachments">
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 form-group">
							<label>Anexos</label>
							<input type="file" name="attachments[]" multiple>
							<p class="help-block">Insira todos os anexos de uma vez, segurando a tecla <kbd>Ctrl</kbd> e clicando sobre cada um</p>
							<p class="help-block">Limite: 3 MB</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="id-ticket" id="id-ticket" value="<?=$id_ticket?>">
					<input type="hidden" name="id-user" id="id-user" value="<?=$user->getIdUser()?>">
					<input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Fechar</button>
					<button type="button" class="btn btn-success" data-dismiss="modal" id="btn-solution-attachments"><i class="fa fa-check"></i>&nbsp; Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>