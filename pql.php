<?php

class pql {

    protected string $query;

    public static function query() {

    }

    public static function query_as(string $className, string $query, ...$params) {
        $instance = new self();
        echo "Type: {$className}\n";
        echo "Query: {$query}\n";
        echo "Parameters: " . implode(', ', $params) . "\n";
        $instance->query = $query;
        return $instance;
    }

    public function fetch_optional() {

    }

    public function fetch_one(SQLite3 $conn) {
        
    }

    public function fetch_all() {

    }

}