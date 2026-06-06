<?php
$host = "127.0.0.1";
$user = "tavernofmeeple_user";
$pass = "+3k}8eaJwl)do0si";
$db_name = "tavernofmeeple_db";

$conn = new mysqli($host, $user, $pass, $db_name, 3306);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

/**
 * Compatibility wrapper for get_result().
 * get_result() requires the mysqlnd driver which is not always
 * available on shared hosting (cPanel). This function falls back
 * to result_metadata + bind_result when mysqlnd is unavailable.
 */
function db_get_result($stmt) {
    if (function_exists('mysqli_stmt_get_result')) {
        $result = @mysqli_stmt_get_result($stmt);
        if ($result !== false) return $result;
    }
    $meta = $stmt->result_metadata();
    if (!$meta) return false;
    $cols = [];
    while ($col = $meta->fetch_field()) $cols[] = $col->name;
    $data = array_fill_keys($cols, null);
    $refs = [];
    foreach ($cols as $col) $refs[] = &$data[$col];
    call_user_func_array([$stmt, 'bind_result'], $refs);
    $rows = [];
    while ($stmt->fetch()) {
        $row = [];
        foreach ($data as $k => $v) $row[$k] = $v;
        $rows[] = $row;
    }
    return new DbResult($rows);
}

class DbResult {
    private $rows;
    public $num_rows;
    private $pos = 0;
    public function __construct($rows) {
        $this->rows   = $rows;
        $this->num_rows = count($rows);
    }
    public function fetch_assoc() {
        return isset($this->rows[$this->pos]) ? $this->rows[$this->pos++] : null;
    }
}
