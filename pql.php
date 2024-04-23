<?php

class pql {

    protected $class;
    protected string $query;
    protected array $params;

    public static function query(string $query, ...$params) {
        $instance = new self();
        $instance->query = $query;
        $instance->params = $params;
        return $instance;
    }

    public static function query_as($class, string $query, ...$params) {
        $instance = new self();
        $instance->class = $class;
        $instance->query = $query;
        $instance->params = $params;
        return $instance;
    }

    public function fetch_optional() {

    }

    public function fetch_one(SQLite3 $conn) {
        $stmt = $conn->prepare($this->query);

        for ($i = 0; $i < count($this->params); $i++) {
            $stmt->bindValue($i+1, $this->params[$i]);
        }

        $result = $stmt->execute();

        $row = self::parse_row($result->fetchArray());

        if (empty($row)) {
            return null;
        }

        if (isset($this->class)) $value = new $this->class($row);
        else $value = $row;

        return $value;
    }

    private static function parse_row($row) {
        $new_row = array();
        if (!is_array($row)) return array();
        for ($i = 0; $i < count($row); $i += 2) {
            $index_key = array_keys($row)[$i];
            $name_key = isset(array_keys($row)[$i + 1]) ? array_keys($row)[$i + 1] : null;

            if (is_numeric($name_key)) $i -= 1;

            $new_row[$name_key] = $row[$index_key];
        }
        return $new_row;
    }

    public function fetch_all(SQLite3 $conn) {
        $stmt = $conn->prepare($this->query);

        for ($i = 0; $i < count($this->params); $i++) {
            $stmt->bindValue($i+1, $this->params[$i]);
        }

        $result = $stmt->execute();

        $first_row = self::parse_row($result->fetchArray());

        if (empty($first_row)) {
            return null;
        }

        $result->reset();

        $rows = array();

        while ($row = self::parse_row($result->fetchArray())) {
            if (isset($this->class)) $rows[] = new $this->class($row);
            else $rows[] = $row;
        }

        return $rows;
    }

}