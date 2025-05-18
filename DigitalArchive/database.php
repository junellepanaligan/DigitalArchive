<?php
class DatabaseManager {
    private $conn;

    public function __construct() {
        // Database connection details
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "accounts_db";

        // Create connection without database
        $this->conn = new mysqli($servername, $username, $password);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // Create database if it doesn't exist
        $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
        if ($this->conn->query($sql) === TRUE) {
            error_log("Database created successfully or already exists");
        } else {
            error_log("Error creating database: " . $this->conn->error);
        }

        // Select the database
        $this->conn->select_db($dbname);

        // Create accounts table if it doesn't exist
        $this->createAccountsTable();
        // Create feedback table if it doesn't exist
        $this->createFeedbackTable();
        // Create hiring records table if it doesn't exist
        $this->createHiringTable();
        // Create notary requests table if it doesn't exist
        $this->createNotaryTable();
    }

    private function createAccountsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS accounts_db (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            course VARCHAR(255),
            email VARCHAR(255) NOT NULL UNIQUE,
            address TEXT,
            average DECIMAL(5,2),
            password VARCHAR(255) NOT NULL,
            age INT,
            contact VARCHAR(20),
            date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if ($this->conn->query($sql) === TRUE) {
            error_log("Accounts table created successfully or already exists");
        } else {
            error_log("Error creating accounts table: " . $this->conn->error);
        }
    }

    private function createFeedbackTable() {
        $sql = "CREATE TABLE IF NOT EXISTS feedback (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            feedback TEXT NOT NULL,
            rating INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if (!$this->conn->query($sql)) {
            die("Error creating feedback table: " . $this->conn->error);
        }
    }

    private function createHiringTable() {
        $sql = "CREATE TABLE IF NOT EXISTS client_hire (
            id INT AUTO_INCREMENT PRIMARY KEY,
            client_id INT NOT NULL,
            client_name VARCHAR(255) NOT NULL,
            client_email VARCHAR(255) NOT NULL,
            lawyer_id INT NOT NULL,
            lawyer_name VARCHAR(255) NOT NULL,
            hire_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            payment_method VARCHAR(50) NOT NULL,
            status VARCHAR(20) DEFAULT 'active',
            FOREIGN KEY (client_id) REFERENCES accounts_db(id)
        )";

        if (!$this->conn->query($sql)) {
            die("Error creating client_hire table: " . $this->conn->error);
        }
    }

    // Method to insert feedback
    public function insertFeedback($username, $feedback, $rating) {
        $stmt = $this->conn->prepare("INSERT INTO feedback (username, feedback, rating) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $username, $feedback, $rating);

        if ($stmt->execute()) {
            $stmt->close();
            return [
                'success' => true,
                'message' => 'Feedback saved successfully'
            ];
        } else {
            $stmt->close();
            return [
                'success' => false,
                'message' => 'Error saving feedback: ' . $this->conn->error
            ];
        }
    }

    // Method to get recent feedbacks
    public function getRecentFeedbacks() {
        $sql = "SELECT * FROM feedback ORDER BY created_at DESC LIMIT 6";
        $result = $this->conn->query($sql);
        return $result;
    }

    // Method to insert a new account
    public function insertAccount($name, $course, $email, $address, $average, $password, $age, $contact) {
        // Prepare SQL statement
        $stmt = $this->conn->prepare("INSERT INTO accounts_db (name, course, email, address, average, password, age, contact, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssdssi", $name, $course, $email, $address, $average, $password, $age, $contact);

        // Execute the statement
        if ($stmt->execute()) {
            $stmt->close();
            return [
                'success' => true,
                'message' => 'Account created successfully'
            ];
        } else {
            $stmt->close();
            return [
                'success' => false,
                'message' => 'Error creating account: ' . $this->conn->error
            ];
        }
    }

    // Method to get account by ID
    public function getAccountById($id) {
        // Prepare SQL statement
        $stmt = $this->conn->prepare("SELECT * FROM accounts_db WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the account
        if ($row = $result->fetch_assoc()) {
            $stmt->close();
            return $row;
        }

        $stmt->close();
        return null;
    }

    // Method to update an account
    public function updateAccount($id, $name, $course, $email, $address, $average, $age, $contact) {
        // Prepare SQL statement
        $stmt = $this->conn->prepare("UPDATE accounts_db SET name = ?, course = ?, email = ?, address = ?, average = ?, age = ?, contact = ? WHERE id = ?");
        $stmt->bind_param("ssssdssi", $name, $course, $email, $address, $average, $age, $contact, $id);

        // Execute the statement
        if ($stmt->execute()) {
            $stmt->close();
            return [
                'success' => true,
                'message' => 'Account updated successfully'
            ];
        } else {
            $stmt->close();
            return [
                'success' => false,
                'message' => 'Error updating account: ' . $this->conn->error
            ];
        }
    }

    // Method to get all accounts
    public function getAllAccounts() {
        $query = "SELECT * FROM accounts_db ORDER BY id ASC";
        return $this->conn->query($query);
    }

    // Method to delete an account
    public function deleteAccount($id) {
        $stmt = $this->conn->prepare("DELETE FROM accounts_db WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $stmt->close();
            return [
                'success' => true,
                'message' => 'Account deleted successfully'
            ];
        } else {
            $stmt->close();
            return [
                'success' => false,
                'message' => 'Error deleting account: ' . $this->conn->error
            ];
        }
    }

    // Add method to check if email exists
    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT id FROM accounts_db WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Add method to authenticate user
    public function authenticateUser($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM accounts_db WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return [
                    'success' => true,
                    'user' => $user
                ];
            }
        }
        
        return [
            'success' => false,
            'message' => 'Invalid email or password'
        ];
    }

    // Method to create a hiring record
    public function createHiringRecord($client_id, $client_name, $client_email, $lawyer_id, $lawyer_name, $payment_method) {
        $stmt = $this->conn->prepare("INSERT INTO client_hire (client_id, client_name, client_email, lawyer_id, lawyer_name, payment_method) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ississ", $client_id, $client_name, $client_email, $lawyer_id, $lawyer_name, $payment_method);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Hiring record created successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error creating hiring record: ' . $this->conn->error
            ];
        }
    }

    // Method to get all hiring records
    public function getAllHiringRecords() {
        $sql = "SELECT * FROM client_hire ORDER BY hire_date DESC";
        $result = $this->conn->query($sql);
        return $result;
    }

    public function createNotaryTable() {
        $sql = "CREATE TABLE IF NOT EXISTS notary_requests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            user_name VARCHAR(255) NOT NULL,
            document_type VARCHAR(50) NOT NULL,
            document_description TEXT,
            document_file VARCHAR(255),
            appointment_date DATE NOT NULL,
            appointment_time TIME NOT NULL,
            additional_notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES accounts_db(id)
        )";
        
        if ($this->conn->query($sql) === TRUE) {
            return true;
        }
        return false;
    }

    public function saveNotaryRequest($user_id, $user_name, $document_type, $document_description, $document_file, $appointment_date, $appointment_time, $additional_notes) {
        $sql = "INSERT INTO notary_requests (user_id, user_name, document_type, document_description, document_file, appointment_date, appointment_time, additional_notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isssssss", $user_id, $user_name, $document_type, $document_description, $document_file, $appointment_date, $appointment_time, $additional_notes);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getNotaryRequests($user_id = null) {
        $sql = "SELECT * FROM notary_requests WHERE 1=1";
        
        if ($user_id) {
            $sql .= " AND user_id = ?";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        
        if ($user_id) {
            $stmt->bind_param("i", $user_id);
        }
        
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>

