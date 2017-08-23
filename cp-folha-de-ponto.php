<?php 

// Arquivo de processamento
require 'headers/cp-header.php';

$id_user = $_POST['id-user'];
$month_and_year = $_POST['txt-date'];

$db = Database::getInstance();

$month = substr($month_and_year, 0, 2);
$year = substr($month_and_year, -4, 4);

$db->query(
    "
    SELECT
        time.type_cp,
        DATE_FORMAT(time.date_cp_timekeeping, '%d/%m/%Y') as date,
        time.entry_time,
        time.exit_time,
        time.break_start,
        time.break_end,
        user.name
    FROM
        tb_cp_timekeeping   time,
        tb_users            user
    WHERE
        time.id_user = ?
    AND
        MONTH(time.date_cp_timekeeping) = ?
    AND
        YEAR(time.date_cp_timekeeping) = ?
    AND
        user.id_user = time.id_user
    "
    ,
    array(
        $id_user,
        $month,
        $year
    )
);

$results = $db->getResults();

// var_dump($db->getResults());
?>

<div class="row">
    <div class="col-xs-12 col-sm-4 title">
        <h1>Folha de ponto</h1>
    </div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <th>Data</th>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Entrada</th>
                    <th>Início do almoço</th>
                    <th>Fim do almoço</th>
                    <th>Saída</th>
                </thead>
                <tbody>
                    <?php
                        foreach($results as $result) {
                            ?>
                            <tr>
                                <td><?=$result->date?></td>
                                <td><?=$result->name?></td>
                                <td><?=$result->type_cp?></td>
                                <td><?=$result->entry_time?></td>
                                <td><?=$result->break_start?></td>
                                <td><?=$result->break_end?></td>
                                <td><?=$result->exit_time?></td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
    require 'scripts/main-script.php';
    require 'footers/main-footer.php';
?>
