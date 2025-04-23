<?php
class Baza {
    private $mysqli; // Obiekt połączenia z bazą danych

    // Konstruktor klasy - tworzy połączenie z bazą danych
    public function __construct($host, $user, $pass, $db) {
        $this->mysqli = new mysqli($host, $user, $pass, $db);
        if ($this->mysqli->connect_error) {
            die("Błąd połączenia z bazą danych: " . $this->mysqli->connect_error);
        }
        $this->mysqli->set_charset("utf8");
    }

    // Funkcja wstawiająca dane do bazy danych za pomocą prepared statements
    public function insert($sql, $types, $params) {
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param($types, ...$params);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Funkcja pobierająca jeden rekord
    public function selectOne($sql, $types, $params) {
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }

    // Funkcja pobierająca dane z bazy danych (w wersji z parametrami)
    public function select($sql, $types = "", $params = []) {
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            return false;
        }

        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result === false) {
            return false;
        }

        // Jeśli wynik zawiera wiersze, zwróć dane
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }

        $stmt->close();
    }

    // Funkcja zamykająca połączenie z bazą danych
    public function close() {
        $this->mysqli->close();
    }
}
