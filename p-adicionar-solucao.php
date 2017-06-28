<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$id_ticket = $_POST['id-ticket'];
$id_user = $_POST['id-user'];
$desc_solution = $_POST['txt-desc-solution'];
$attachments = $_POST['attachments'];

$solution = new Solution(null, $id_ticket, $id_user, $desc_solution, null);

$dao = new SolutionDAO();
$dao->deleteSolution($id_ticket);
$dao->createSolution($solution);

$lastInsertId = $db->getLastInsertId();

// if ($_FILES['attachments']['tmp_name'][0] != "") {
//     $myFile = $_FILES['attachments'];
//     $fileCount = count($myFile["name"]);

//     for ($i = 0; $i < $fileCount; $i++) {
//         $size = $_FILES['attachments']['size'][$i];
//         if ($size > 3000000){
//             $dao->deleteSolution($lastInsertId);
//             echo "O limite de tamanho é de 3 MB";
//             exit();
//         }
//     }

//     $dao = new SolutionAttachmentDAO;

//     $path = "solutions/" . $lastInsertId;
//     mkdir($path);

//     for ($i = 0; $i < $fileCount; $i++) {
//         $name = $_FILES['attachments']['name'][$i];
//         $new_path = $path . "/" . $name;

//         move_uploaded_file($_FILES['attachments']['tmp_name'][$i], $new_path);
//         $dao->createSolutionAttachment($lastInsertId, $name, $new_path);
//     }
// }

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistory($id_ticket, $id_user, "Solução adicionada por");

echo "success";