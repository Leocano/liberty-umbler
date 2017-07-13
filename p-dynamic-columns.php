<?php 

require 'core/initializer.php';

$column = $_POST['column'];

$column_name = explode('.', $column);

$db = Database::getInstance();

$db->query(
    "
    SELECT
        query_column
    FROM
        tb_report_columns
    WHERE
        name_column = ?
    ",
    array($column_name[1])
);

$query_column = $db->getResults();

if ($query_column[0]->query_column == null) {
    echo json_encode("null");
} else {
    $db->query($query_column[0]->query_column);
    $query_values = $db->getResults();
    $column_array = array();
    foreach($query_values as $val) {
        array_push($column_array, $val->$column_name[1]);
    }
    echo json_encode($column_array);
}