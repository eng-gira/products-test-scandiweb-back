<?php

namespace Data;

use Data\DB;

class QueryBuilder {
    private string $query;
    private array $whereClause = [];
    private string $order = "";
    private string $limit = ";";

    public function select($table, $cols) {
        $this->query = 'SELECT ';
        for($i=0; $i < count($cols); $i++) 
        {
            $this->query .= $cols[$i];
            if($i != count($cols) - 1) $this->query .= ', ';
        }
        $this->query .= ' FROM ' . $table;

        return $this;
    }

    public function insert($table, array $colsVals) {
        $this->query = "INSERT INTO $table ";

        $cols = '';
        $vals = '';
        $counter = 0;
        foreach($colsVals as $c=>$v) {
            $cols .= $c;
            $vals .= $v;

            if($counter != count($colsVals) - 1) {
                $cols .= ',';
                $vals .= ',';
            }
        }

        $this->query .= "$cols VALUES ($vals);";
    }

    public function where($k, $op, $v, $dataType) {
        array_push($this->whereClause, ['k'=>$k, 'op'=>$op, 'v' =>$v, 'dataType' => $dataType]);
    }

    public function orderBy($col, $dir = 'ASC') {
        $this->order = "ORDER BY $col $dir ";
    }

    public function setLimit(int $num) {
        $this->limit = "LIMIT $num;";
    }

    public function run() {
        $query = $this->query;
        $bindParamsTypes = '';
        $valsToBind = [];

        for($i=0; $i<count($this->whereClause); $i++) {
            $clause = $this->whereClause[$i];

            $bindParamsTypes .= $clause['dataType'];
            array_push($valsToBind, $clause['v']);

            if($i == count($this->whereClause) - 1) $query .= 'WHERE ' . $clause['k'] . $clause['op'] . '? ';
            else $query .= 'OR WHERE' . $clause['k'] . $clause['op'] . '? ';
        }

        $query .= $this->order . $this->limit;

        $conn = DB::connect();

        if($stmt = $conn->prepare($query))
        {
            $stmt->bind_param($bindParamsTypes, ...$valsToBind);
            if($stmt->execute()) {
                return $stmt->insert_id;
            }
        }
        return false;
    }
}