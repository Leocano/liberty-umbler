<?php 

class BillingFactory{
    private $columns;
    private $from;
    private $where;
    private $results;
    private $grouping;
    private $sum;
    private $query;
    private $hour_pos;

    public function getBillingReports(){
        $db = Database::getInstance();

        $db->query(
            "
            SELECT
                *
            FROM
                tb_reports      repo
            LIMIT 10            
            "
        );

        return $db->getResults();
    }

    // public function generateXlsReport($reports){
        
    // }
}
