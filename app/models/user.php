<?php

class User {
    // Inayos ang property name para mag-match sa ginagamit sa baba
    protected mysqli $conn; 
    protected string $table = 'phphr_users';

    public function __construct(mysqli $mysql_conn) {
        // Siguraduhin na ang ipinasa ay naka-assign sa tamang property
        $this->conn = $mysql_conn;
    }

    public function all(): array {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        // Safety check kung valid ang result bago mag fetch
        return ($result instanceof mysqli_result) ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function find(int $id): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function findByUsername(string $username): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    ///new update create function 
    public function create(array $data): bool {
        $sql = "INSERT INTO {$this->table} (username, password_hash, full_name, status, role, login_status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            // Kapag pumasok dito, ibig sabihin MALI ang SQL String o Column Names mo.
            die("<h1>SQL Prepare Failed!</h1>" . 
                "<b>Error:</b> " . $this->conn->error . "<br>" .
                "<b>SQL:</b> " . $sql);
        }

        $loginStatus = $data['login_status'] ?? 0;
        $stmt->bind_param('sssisi', 
            $data['username'], 
            $data['password_hash'], 
            $data['full_name'], 
            $data['status'], 
            $data['role'],
            $loginStatus
        );

        $success = $stmt->execute();

        if (!$success) {
            die("<h1>Execute Failed!</h1>" . "<b>Error:</b> " . $stmt->error);
        }

        return $success;
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE {$this->table} SET username=?, password_hash=?, full_name=?, status=?, role=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        
        // laging i-double check ang sequence: s(user), s(pass), s(name), i(status), s(role), i(id)
        $stmt->bind_param('sssisi', 
            $data['username'], 
            $data['password_hash'], 
            $data['full_name'], 
            $data['status'], 
            $data['role'], 
            $id
        );
        
        return $stmt->execute();
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /*** Update login status (1 for Online, 0 for Offline)*/
    public function updateLoginStatus(int $userId, int $status): bool {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET login_status = ?, last_login = NOW() WHERE id = ?");
        $stmt->bind_param('ii', $status, $userId);
        return $stmt->execute();
    }
}