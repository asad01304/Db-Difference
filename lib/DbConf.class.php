<?php
/**
 * Created by JetBrains PhpStorm.
 * User: asad-rahman
 * Date: 5/31/13
 * Time: 12:17 AM
 * To change this template use File | Settings | File Templates.
 */

Class DbConf{

    private $con;
    private $db;

    public function __construct($user, $pass, $host, $db){

        $this->db  = $db;
        $this->con = mysql_connect($host, $user, $pass, true);

        if (!$this->con) {
            throw new Exception("Failed to connect DB: {$db}");
        }

        mysql_select_db($this->db);
    }

    public function __destruct(){
        mysql_close($this->con);
    }

    public function analyzeDb(){

        $result = @mysql_list_tables($this->db, $this->con);

        while ($row = mysql_fetch_row($result)) {

            $tblName        = $row[0];
            $info[$tblName] = $this->getTableDefinition($tblName);
        }

        return $info;
    }

    private function getTableDefinition($tblName){

        $info   = array();
        $result = mysql_query("select * from {$tblName}", $this->con);

        if(!$result){
            return $info;
        }

        $i = 0;
        while ($i < mysql_num_fields($result)) {

            $meta = mysql_fetch_field($result, $i);
            $i++;

            if(!$meta) {
                continue;
            }

            $info[(string)$meta->name] = $meta;
        }
        mysql_free_result($result);
        return $info;
    }

}