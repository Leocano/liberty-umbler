<?php 
 
// Inclui o arquivo class.phpmailer.php localizado na pasta class
require_once("plugins/php-mailer/class.phpmailer.php");
require_once("plugins/php-mailer/class.smtp.php");
require_once("plugins/php-mailer/PHPMailerAutoload.php");

//Conectando no banco
$mail_db = new PDO('mysql:host=179.188.16.164;dbname=letnis42', 'letnis42', 'Letnis@*42');
$query = $mail_db->prepare("SELECT * FROM tb_website_mail");
$query->execute();
$login_settings = $query->fetchAll(PDO::FETCH_OBJ);

// Inicia a classe PHPMailer
$mail = new PHPMailer(true);
 
// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsSMTP(); // Define que a mensagem será SMTP
 
try {
     $mail->CharSet = 'UTF-8';
     $mail->Host = $login_settings[0]->server ; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
     $mail->SMTPAuth   = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
     $mail->Port       = 587; //  Usar 587 porta SMTP
     $mail->Username = $login_settings[0]->username ; // Usuário do servidor SMTP (endereço de email)
     $mail->Password = $login_settings[0]->password ; // Senha do servidor SMTP (senha do email usado)
 
     //Define o remetente
     // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=    
     $mail->SetFrom('helpdesk@letnis.com.br', 'LIBERTY - Help Desk'); //Seu e-mail
     $mail->Subject = '[LETNIS] O chamado ' . $id_ticket . ' foi atribuído a você! - Prioridade: ' . $priority;//Assunto do e-mail
 
 
     //Define os destinatário(s)
     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
     foreach ($user_emails as $email) {
        $mail->AddAddress($email->email, $email->name);
     }
      
     //Campos abaixo são opcionais 
     //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
     // $mail->AddCC('rodrigo.souza@letnis.com.br', 'Rodrigo Souza'); 
     //$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
     //$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo
 
 
     //Define o corpo do email
     $mail->MsgHTML(
        "
        Prezado(a) Consultor(a):
        <br>
        <br>
        <br>
        Um novo chamado foi atribuído a sua fila de atendimento. Segue os detalhes:
        <br>
        <br>
        <b>Criado por:</b> " . $created_by . "
        <br>
        <b>Prioridade:</b> " . $priority . "
        <br>
        <b>Categoria:</b> " . $category . "
        <br>
        <b>Assunto:</b> " . $subject . "
        <br>
        <br>
        <b>Descrição:</b> " . $description . "
        <br>
        <br>
        <a href='http://www.letnis.com.br/liberty/visualizar-chamado.php?id=" . $id_ticket . "'>Clique aqui para acessar o chamado</a>
        <br>
        <br>
        <u><b>Check List de atendimento:</b></u>
        <br>
        <ul>
            <li>Receber chamado</li>
            <li>Checar prioridade</li>
            <li>Ler detalhes no Support Center (anexos)</li>
            <li>Entender (pensando em todos os detalhes)</li>
            <li>Verificar se informações são suficientes para o atendimento</li>
            <li>Entrar em contato com o cliente</li>
            <li>Desenhar solução</li>
            <li>Executar solução de forma objetiva</li>
            <li>Fazer documentação</li>
            <li>Reportar solução de forma clara e com bom português</li>
            <li>Efetuar apontamentos claros e objetivos</li>
            <li>Acompanhar chamado até o encerramento (FUP)</li>
        </ul>
        <br>
        <br>
        <u><b>Atenção para os níveis de SLA:</b></u>
        <br>
        <br>
        <b>HIGH:</b>
        <br>
        Primeiro contato: até 1 hora
        <br>
        Resolução: até 8 horas
        <br>
        <br>
        <b>NORMAL:</b>
        <br>
        Primeiro contato: até 2 horas
        <br>
        Resolução: até 16 horas
        <br>
        <br>
        <b>LOW:</b>
        <br>
        Primeiro contato: até 8 horas
        <br>
        Resolução até 36 horas
        <br>
        <br>
        Atenciosamente,
        <br>
        <b>LETNIS - Célula de Suporte</b>
        "
    ); 
 
     ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
     // $mail->MsgHTML(file_get_contents('corpo-email.html'));
 
     $mail->Send();
     $success = true;
 
    //caso apresente algum erro é apresentado abaixo com essa exceção.
    } catch (phpmailerException $e) {
      echo $e->errorMessage(); //Mensagem de erro customizada do PHPMailer
      $success = false;
	}
?>