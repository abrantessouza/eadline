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

    public function insert(){
        $this->query = "INSERT INTO {$this->table} ";
        return $this;
    }

    public function addValues(Array $cols, Array $values){
        $strCols = join(",",$cols);
        $strValues = "'". join("','",$values)."'";
        $this->query .= "({$strCols}) VALUES ({$strValues})" ;
        return $this;
    }

    public function run(){
        try{
            $stm =  $this->db->prepare($this->query);
            if(strpos($this->query, 'INSERT INTO')!==false){
                $this->result=$stm->execute();
            }else{
                $stm->execute();
                $this->result = $stm->fetchAll(\PDO::FETCH_ASSOC);
            }
            return $this->result;

        }catch(\PDOException $e){
            return $e->getMessage();
        }

    }



}