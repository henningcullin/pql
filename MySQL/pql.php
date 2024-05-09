<?php

class FromRow
{
    public function __construct(array &$row)
    {
        echo '<hr>';
        echo '<pre>';
        var_dump($row);
        echo '</pre>';
    }
}

class pql
{
    protected $class;
    protected string $query;
    protected array $params;

    public static function query_as(&$class, string &$query, &...$params)
    {
        $query = $query;
        $instance = new self();
        $instance->class = $class;
        $instance->query = $query;
        $instance->params = $params;
        return $instance;
    }

    public function fetch_all(mysqli $conn)
    {
        $stmt = $conn->prepare($this->query);

        $stmt->execute();

        $result = $stmt->get_result();

        $rows = array();

        while ($row = $result->fetch_assoc()) {
            if (isset($this->class)) $rows[] = new $this->class($row);
            else $rows[] = $row;
        }

        return $rows;
    }
}
