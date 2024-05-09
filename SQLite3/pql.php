<?php

class FromRow
{
    public function __construct(array $row)
    {
        $this_class = strtolower(static::class);
        foreach ($row[$this_class] as $key => &$value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        unset($row[$this_class]);
        foreach ($row as $class => &$contents) {
            if (property_exists($this, $class)) {
                $this->$class = new $class($row);
            }
        }
    }
}

class pql
{

    protected $class;
    protected string $query;
    protected array $params;

    public static function query(string $query, ...$params)
    {
        $instance = new self();
        $instance->query = $query;
        $instance->params = $params;
        return $instance;
    }

    public static function query_as($class, string $query, ...$params)
    {
        $query = self::parse_query($query);
        $instance = new self();
        $instance->class = $class;
        $instance->query = $query;
        $instance->params = $params;
        return $instance;
    }

    public function fetch_optional()
    {
    }

    public function fetch_one(SQLite3 $conn)
    {
        $stmt = $conn->prepare($this->query);

        for ($i = 0; $i < count($this->params); $i++) {
            $stmt->bindValue($i + 1, $this->params[$i]);
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

    public function fetch_all(SQLite3 $conn)
    {
        $stmt = $conn->prepare($this->query);

        for ($i = 0; $i < count($this->params); $i++) {
            $stmt->bindValue($i + 1, $this->params[$i]);
        }

        $result = $stmt->execute();

        $first_row = $result->fetchArray(SQLITE3_ASSOC);

        if (empty($first_row)) {
            return null;
        }

        $result->reset();

        $rows = array();

        while ($row = self::parse_row($result->fetchArray(SQLITE3_ASSOC))) {
            if (isset($this->class)) $rows[] = new $this->class($row);
            else $rows[] = $row;
        }

        return $rows;
    }

    private static function add_aliases($query)
    {
        $query_parts = preg_split('/from/i', $query, 2);

        // Regular expression to match column names
        $column_pattern = '/(\w+)\.(\w+)/';

        // Find all matches in the query
        preg_match_all($column_pattern, $query_parts[0], $matches);

        // Create an associative array to store column aliases
        $alias_dict = [];

        // Generate column aliases
        foreach ($matches[0] as $match) {
            list($table, $column) = explode('.', $match);
            $alias = "{$table}.{$column}";
            $alias_dict[$match] = "{$alias} AS {$table}_{$column}";
        }

        // Replace column names with aliases in the query
        foreach ($alias_dict as $original => $alias) {
            $query_parts[0] = str_replace($original, $alias, $query_parts[0]);
        }

        $query = implode('FROM', $query_parts);

        return $query;
    }

    private static function parse_query($query)
    {
        if (str_contains($query, '.')) {
            $query = self::add_aliases($query);
        }
        return $query;
    }

    private static function parse_row($row)
    {
        $new_row = array();

        if (!is_array($row)) return $new_row;

        foreach ($row as $key => $val) {
            $key_arr = explode('_', $key);
            $new_row[$key_arr[0]][$key_arr[1]] = $val;
        }

        return $new_row;
    }
}
