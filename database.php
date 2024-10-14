<?php

class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct()
    {
        $this->host = 'localhost';
        $this->db_name = 'shop';
        $this->username = 'root';
        $this->password = '';
    }

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            throw new PDOException("Connection error: " . $exception->getMessage());
        }

        return $this->conn;
    }

    private function disconnect()
    {
        $this->conn = null;
    }

    public function create($table, $data)
    {
        try {
            $this->getConnection(); // เชื่อมต่อก่อนเริ่มทำงาน

            $fields = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));
            $sql = "INSERT INTO $table ($fields) VALUES ($values)";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute($data);
        } catch (PDOException $e) {
            // throw new PDOException("Create error: " . $e->getMessage());
            return $e->getMessage();
        } finally {
            $this->disconnect(); // ปิดการเชื่อมต่อเสมอ
        }

        return $result;
    }

    public function read($sql, $params = [])
    {
        try {
            $this->getConnection();

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new PDOException("Read error: " . $e->getMessage());
        } finally {
            $this->disconnect();
        }

        return $result;
    }

    public function update_delete($sql, $params)
    {
        try {
            $this->getConnection();

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            throw new PDOException("SQL error: " . $e->getMessage());
        } finally {
            $this->disconnect();
        }

        return true;
    }
}
