<?php

abstract class Model {
    // Subclass must define this: protected static $table = 'table_name';
    protected static $table;

    // Override in subclass if primary key column is not 'id'
    protected static $primaryKey = 'id';

    // Override in subclass to set a default ORDER BY clause, e.g. 'name' or 'title DESC'
    protected static $orderBy = null;

    // Per-request cache: ['table_name' => ['col1', 'col2', ...]]
    private static $columnCache = [];

    protected $db;

    public function __construct($data = []) {
        $this->db = DB::getInstance()->getConnection();

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }

    // Returns the table name; throws if subclass forgot to define $table
    protected static function getTable(): string {
        if (empty(static::$table)) {
            throw new Exception(static::class . ' must define protected static $table.');
        }
        return static::$table;
    }

    // Runs DESCRIBE once per table per request and caches the column names
    protected static function getColumns(): array {
        $table = static::getTable();
        if (!isset(self::$columnCache[$table])) {
            $db = DB::getInstance()->getConnection();
            $stmt = $db->query("DESCRIBE `{$table}`");
            // PDO::FETCH_COLUMN on column 0 returns just the Field (column name) values
            self::$columnCache[$table] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        return self::$columnCache[$table];
    }

    // All columns except the primary key — used to build INSERT / UPDATE statements
    protected static function getNonKeyColumns(): array {
        $pk = static::$primaryKey;
        return array_values(array_filter(static::getColumns(), fn($c) => $c !== $pk));
    }

    // Return all rows as an array of subclass instances
    public static function findAll(): array {
        $db  = DB::getInstance()->getConnection();
        $sql = "SELECT * FROM `" . static::getTable() . "`";
        if (static::$orderBy) {
            $sql .= " ORDER BY " . static::$orderBy;
        }
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new static($row);
        }
        return $results;
    }

    // Return a single row by primary key, or null if not found
    public static function findById($id): ?static {
        $db    = DB::getInstance()->getConnection();
        $table = static::getTable();
        $pk    = static::$primaryKey;

        $stmt = $db->prepare("SELECT * FROM `{$table}` WHERE `{$pk}` = :id");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();
        return $row ? new static($row) : null;
    }

    // Insert (when PK is null) or update (when PK is set) the current object
    public function save(): void {
        $table   = static::getTable();
        $pk      = static::$primaryKey;
        $columns = static::getNonKeyColumns();

        $params = [];
        foreach ($columns as $c) {
            $params[$c] = $this->$c;
        }

        if ($this->$pk) {
            // UPDATE existing row
            $sets = [];
            foreach ($columns as $c) {
                $sets[] = "`{$c}` = :{$c}";
            }
            $sets = implode(', ', $sets);
            $sql = "UPDATE `{$table}` SET " . $sets . " WHERE `{$pk}` = :{$pk}";
            $params[$pk] = $this->$pk;
        } else {
            // INSERT new row
            $cols         = [];
            $placeholders = [];
            foreach ($columns as $c) {
                $cols[]         = "`{$c}`";
                $placeholders[] = ":{$c}";
            }
            $cols         = implode(', ', $cols);
            $placeholders = implode(', ', $placeholders);
            $sql = "INSERT INTO `{$table}` (" . $cols . ") VALUES (" . $placeholders . ")";
        }

        $stmt   = $this->db->prepare($sql);
        $status = $stmt->execute($params);

        if (!$status) {
            $info = $stmt->errorInfo();
            throw new Exception(sprintf("SQLSTATE %s: %s", $info[0], $info[2]));
        }

        if ($stmt->rowCount() !== 1) {
            throw new Exception("Failed to save " . static::class . ".");
        }

        // Capture the auto-generated ID for newly inserted rows
        if ($this->$pk === null) {
            $this->$pk = $this->db->lastInsertId();
        }
    }

    // Delete the row for this object; returns false if PK is not set
    public function delete(): bool {
        $pk = static::$primaryKey;
        if (!$this->$pk) {
            return false;
        }
        $stmt = $this->db->prepare(
            "DELETE FROM `" . static::getTable() . "` WHERE `{$pk}` = :id"
        );
        return $stmt->execute(['id' => $this->$pk]);
    }

    // One-to-many: fetch all related rows where $foreignKey = this PK
    // Usage in subclass: return $this->hasMany(Game::class, 'genre_id');
    protected function hasMany(string $class, string $foreignKey): array {
        $table = $class::getTable();
        $pk    = static::$primaryKey;
        $stmt  = $this->db->prepare("SELECT * FROM `{$table}` WHERE `{$foreignKey}` = :id");
        $stmt->execute(['id' => $this->$pk]);
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new $class($row);
        }
        return $results;
    }

    // Inverse of hasMany: fetch the single parent row this object belongs to
    // Usage in subclass: return $this->belongsTo(Genre::class, 'genre_id');
    protected function belongsTo(string $class, string $foreignKey): ?object {
        return $class::findById($this->$foreignKey);
    }

    // Many-to-many via a pivot table
    // $fk  = pivot column pointing to THIS model  (e.g. 'game_id')
    // $rfk = pivot column pointing to RELATED model (e.g. 'platform_id')
    // Usage in subclass: return $this->belongsToMany(Platform::class, 'game_platform', 'game_id', 'platform_id');
    protected function belongsToMany(string $class, string $pivot, string $fk, string $rfk): array {
        $table = $class::getTable();
        $rpk   = $class::$primaryKey;
        $pk    = static::$primaryKey;
        $stmt  = $this->db->prepare(
            "SELECT t.* FROM `{$table}` t
             INNER JOIN `{$pivot}` p ON t.`{$rpk}` = p.`{$rfk}`
             WHERE p.`{$fk}` = :id"
        );
        $stmt->execute(['id' => $this->$pk]);
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new $class($row);
        }
        return $results;
    }

    // Convert the object to an associative array keyed by column name
    public function toArray(): array {
        $result = [];
        foreach (static::getColumns() as $column) {
            $result[$column] = $this->$column;
        }
        return $result;
    }
}
