<?php

class pql {

    protected string $className;
    protected string $query;
    protected array $params;

    public static function query(string $query, ...$params) {
        $instance = new self();
        $instance->query = $query;
        $instance->params = $params;
        return $instance;
    }

    public static function query_as(string $className, string $query, ...$params) {
        $instance = new self();
        $instance->className = $className;
        $instance->query = $query;
        $instance->params = $params;
        return $instance;
    }

    public function fetch_optional() {

    }

    public function fetch_one(SQLite3 $conn) {
        $stmt = $conn->prepare($this->query);

        for ($i = 1; $i <= count($this->params); $i++) {
            $stmt->bindParam($i, $this->params[$i]);
        }
        
        $result = $stmt->execute();

        $row = $result->fetchArray(SQLITE3_ASSOC);

        if (isset($this->className)) $value = new $this->className($row);
        else $value = $row;

        return $value;
    }

    public function fetch_all(SQLite3 $conn) {
        $stmt = $conn->prepare($this->query);

        for ($i = 1; $i <= count($this->params); $i++) {
            $stmt->bindParam($i, $this->params[$i]);
        }

        $result = $stmt->execute();

        $rows = array();

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            if (isset($this->className)) $rows[] = new $this->className($row);
            else $rows[] = $row;
        }

        return $rows;
    }

}