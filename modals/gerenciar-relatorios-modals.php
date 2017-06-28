<div class="modal fade" tabindex="-1" role="dialog" id="modal-delete">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Deletar Relatório</h4>
      </div>
      <div class="modal-body">
        <p>Deseja mesmo deletar o relatório "<strong><span id="delete-text"></span></strong>" ?</p>
      </div>
      <div class="modal-footer">
        <form name="form-delete-report" id="form-delete-report" action="p-deletar-cliente.php" method="post">
          <input type="hidden" name="txt-delete-id" id="txt-delete-id">
          <input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Fechar</button>
          <button type="button" id="btn-delete-report" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-trash"></i>&nbsp; Deletar</button>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->