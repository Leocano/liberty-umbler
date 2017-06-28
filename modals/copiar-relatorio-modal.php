<div class="modal fade" tabindex="-1" role="dialog" id="modal-copy">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Copiar Relatório</h4>
      </div>
      <div class="modal-body">
        <form id="form-copy-report" method="post" action="p-copiar-relatorio.php">
          <div class="form-group">
            <label>Qual nome deseja dar para esta cópia?</label>
            <input class="form-control" type="text" name="txt-copy-name" id="txt-copy-name">
            <input type="hidden" name="id-report" value="<?=$id_report?>">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Fechar</button>
        <button type="submit" id="btn-confirm-copy" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; Copiar</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->