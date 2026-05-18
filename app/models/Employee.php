<?php

class Employee
{
    protected mysqli $mysql_conn;
    protected string $table = 'phphr_employees';

    public function __construct(mysqli $mysql_conn)
    {
        $this->conn = $mysql_conn;
    }

    /*** Check if employee exists by name and birthdate***/
    public function exists($fname, $lname, $bdate): bool
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE first_name = ? AND last_name = ? AND date_of_birth = ? AND is_deleted = 0 LIMIT 1");
        $stmt->bind_param('sss', $fname, $lname, $bdate);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
        
        return $exists;
    }

    /* ========================
     * FETCH METHODS
     * ======================== */

    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_deleted = 0 ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getDeleted(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_deleted = 1 ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result ?: null;
    }

    public function allActive(): array
    {
        $sql = "SELECT id, employee_code, first_name, last_name
                FROM {$this->table}
                WHERE status = 1 AND is_deleted = 0
                ORDER BY first_name ASC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /* ========================
     * CREATE
     * ======================== */
public function create(array $data): bool {

    $sql = "INSERT INTO {$this->table} (
        user_id, employee_code, CardID,
        first_name, middle_name, last_name, 
        date_of_birth, gender, nationality, phone, email_address, 
        current_address, emergency_name, emergency_number, emergency_relationship, 
        department, designation, date_of_joining, probation_end_date, 
        employment_type, reporting_manager, work_location, salary, 
        hmo, ef, 
        status, ID_filename, is_deleted, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW())";

    $stmt = $this->conn->prepare($sql);

    //$types = 'issssssssssssssssssssssddsss'; // ✅ FIXED
    $types = str_repeat('s', 27); // 🔥 AUTO MATCH

    $stmt->bind_param(
        $types,
        $data['user_id'],
        $data['employee_code'],
        $data['CardID'],
        $data['first_name'],
        $data['middle_name'],
        $data['last_name'],
        $data['date_of_birth'],
        $data['gender'],
        $data['nationality'],
        $data['phone'],
        $data['email_address'],
        $data['current_address'],
        $data['emergency_name'],
        $data['emergency_number'],
        $data['emergency_relationship'],
        $data['department'],
        $data['designation'],
        $data['date_of_joining'],
        $data['probation_end_date'],
        $data['employment_type'],
        $data['reporting_manager'],
        $data['work_location'],
        $data['salary'],
        $data['hmo'],
        $data['ef'],
        $data['status'],
        $data['ID_filename']
    );

    if (!$stmt->execute()) {
        die("SQL ERROR: " . $stmt->error);
    }

    $stmt->close();
    return true;
}
    /* ========================
     * UPDATE
     * ======================== */
public function update(int $id, array $data): bool {

    $sql = "UPDATE {$this->table} SET
        employee_code = ?, 
        CardID = ?,
        first_name = ?, 
        middle_name = ?, 
        last_name = ?, 
        date_of_birth = ?,
        gender = ?, 
        civil_status = ?,
        nationality = ?,
        religion = ?,
        phone = ?, 
        email_address = ?,
        current_address = ?, 
        emergency_name = ?, 
        emergency_number = ?,
        emergency_relationship = ?, 
        department = ?, 
        designation = ?,
        date_of_joining = ?, 
        probation_end_date = ?, 
        employment_type = ?,
        reporting_manager = ?, 
        work_location = ?, 
        salary = ?,
        hmo = ?, 
        ef = ?, 
        status = ?, 
        ID_filename = ?
    WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    // Mayroon tayong 28 na "?" sa SET clause + 1 sa WHERE clause = 29 total parameters.
    // Lahat ay gagawin nating 's' maliban sa huling 'i' para sa ID.
    $types = str_repeat('s', 28) . 'i';

    $stmt->bind_param(
        $types,
        $data['employee_code'],    // 1
        $data['CardID'],           // 2
        $data['first_name'],       // 3
        $data['middle_name'],      // 4
        $data['last_name'],        // 5
        $data['date_of_birth'],    // 6
        $data['gender'],           // 7
        $data['civil_status'],     // 8 - DAGDAG ITO
        $data['nationality'],      // 9
        $data['religion'],         // 10 - DAGDAG ITO
        $data['phone'],            // 11
        $data['email_address'],    // 12
        $data['current_address'],  // 13
        $data['emergency_name'],   // 14
        $data['emergency_number'], // 15
        $data['emergency_relationship'], // 16
        $data['department'],       // 17
        $data['designation'],      // 18
        $data['date_of_joining'],  // 19
        $data['probation_end_date'], // 20
        $data['employment_type'],  // 21
        $data['reporting_manager'], // 22
        $data['work_location'],    // 23
        $data['salary'],           // 24
        $data['hmo'],              // 25
        $data['ef'],               // 26
        $data['status'],           // 27
        $data['ID_filename'],      // 28
        $id                        // 29 (WHERE clause)
    );

    if (!$stmt->execute()) {
        die("SQL ERROR: " . $stmt->error);
    }

    $stmt->close();
    return true;
}

    /* ========================
     * DELETE & RESTORE ACTIONS
     * ======================== */

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET is_deleted = 1 WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function restore(int $id): bool
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET is_deleted = 0 WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function forceDelete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
    
}