<?php
class Database {
    private static $instance = null;
    private $conn;
    private $db_host = 'localhost';
    private $db_user = 'root';
    private $db_pass = '';
    private $db_name = 'cara';

    private function __construct() {
        $this->connect();
    }

    private function connect() {
        if ($this->conn === null) {
            $this->conn = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
            
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset("utf8mb4");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        if (!$this->conn || !$this->conn->ping()) {
            $this->connect();
        }
        return $this->conn;
    }

    public function query($sql) {
        if (!$this->conn || !$this->conn->ping()) {
            $this->connect();
        }
        return $this->conn->query($sql);
    }

    public function prepare($sql) {
        if (!$this->conn || !$this->conn->ping()) {
            $this->connect();
        }
        return $this->conn->prepare($sql);
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?> 