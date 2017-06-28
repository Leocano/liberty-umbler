<?php 

require 'headers/index-header.php';

?>
	<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-lg-4 col-lg-offset-4 text-center index-box">
		<div class="row">
			<div class="col-xs-12 text-center">
				<!-- <a href="#">
					<img src="assets/images/logo-2.png">
				</a> -->
				<div class="index-title">
					<span class="main-title"><span class="glyphicon glyphicon-leaf"></span> Liberty</span>
					<div class="clean"></div>
					<span class="bottom-title">Letnis Help Desk</span>
				</div>
			</div>
			<div class="col-xs-12 index-form">
				<form name="form-login" id="form-login" method="post" action="p-login.php">
					<div class="text-left div-error <?php if (!isset($_GET['error']) && $_GET['error'] != 1) { echo hidden; }?>">
						<b>Dados inválidos</b>
					</div>
					<div class="form-group">
						<div class="col-xs-12 input-group index-group">
							<span class="input-group-addon hidden-xs">
								<i class="fa fa-user fa-lg fa-fw"></i>
							</span>
							<input type="text" class="form-control input-lg <?php if(isset($_GET['error']) && $_GET['error'] == 1){ echo 'input-error';} ?>" name="txt-email" placeholder="E-mail" required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12 input-group index-group">
							<span class="input-group-addon hidden-xs">
								<i class="fa fa-lock fa-lg fa-fw"></i>
							</span>
							<input type="password" class="form-control input-lg <?php if(isset($_GET['error']) && $_GET['error'] == 1){ echo 'input-error';} ?>" name="txt-password" placeholder="Senha" required> 
						</div>
					</div>
					<input type="hidden" name="token" value="<?=$_SESSION['token']?>">
					<div>
						<button class="btn btn-block btn-main btn-lg" type="submit" name="btn-logar">
							<i class="fa fa-sign-in fa-lg"></i>&nbsp;&nbsp;&nbsp;Login
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php 
require_once 'scripts/index-script.php';
?>