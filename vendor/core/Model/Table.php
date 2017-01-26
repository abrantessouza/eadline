<?php
/**
 * Created by PhpStorm.
 * User: Thiago
 * Date: 26/01/2017
 * Time: 17:13
 */

namespace Eadline\Model;


class Table
{
    protected $db;
    protected $table;

    private $query;
    private $result;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function innerjoin($table, Array $on){
        $strOn1 = $this->table.".".$on[0];
        $strOn2 = $table.".".$on[1];
        $this->query .= "INNER JOIN {$table} ON ".$strOn1."=".$strOn2;
        return $this;
    }

    public function where($col, $operator = "=" ,$value, $and = ""){
        $this->query .= "WHERE {$col} {$operator} {$value} {$and}";
        return $this;
    }

    public function select(){
        $this->query = "SELECT * FROM {$this->table} ";
        return $this;
    }

    public function fetch(){
        $stm =  $this->db->prepare($this->query);
        $stm->execute();
        $this->result = $stm->fetch(\PDO::FETCH_ASSOC);
        return $this->result;
    }

}