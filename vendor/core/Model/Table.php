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
    private $columns;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
        $this->allColumns = "*";
    }

    public function innerjoin($table, Array $on){
        $strOn1 = $this->table.".".$on[0];
        $strOn2 = $table.".".$on[1];
        $this->query .= "INNER JOIN {$table} ON ".$strOn1."=".$strOn2;
        return $this;
    }

    public function where($col, $operator = "=" ,$value, $and = ""){
        $this->query .= " WHERE {$col} {$operator} '{$value}' {$and}";
        return $this;
    }

    public function columns(array $cols){
        $columns = join(",",$cols);
        $this->query = str_replace("*", "{$columns}", $this->query);
        return $this;
    }


    public function select(){
        $this->query = "SELECT {$this->allColumns} FROM {$this->table} ";
        return $this;
    }

    public function insert(){
        $this->query = "INSERT INTO {$this->table} ";
        return $this;
    }

    public function update(){
        $this->query = "UPDATE {$this->table} SET ";
        return $this;
    }

    public function setColumns(array $dict){
        $vals = implode(",", array_map(function($key, $val){
            return sprintf("%s='%s'", $key, $val);
        },array_keys($dict), $dict));
        $this->query .= $vals;
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
            if(strpos($this->query, 'INSERT INTO')!==false || strpos($this->query, 'UPDATE')!==false  ){
                $this->result=$stm->execute();
            }else if(strpos($this->query, 'SELECT')!==false){
                $stm->execute();
                $this->result = $stm->fetchAll(\PDO::FETCH_ASSOC);
            }
            return $this->result;

        }catch(\PDOException $e){
            return $e->getMessage();
        }

    }



}