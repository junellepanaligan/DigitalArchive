<?php
require_once 'database.php';

// Initialize DatabaseManager
$dbManager = new DatabaseManager();

// Initialize variables
$message = '';
$messageType = '';
$editAccount = null;

// Handle Delete Action
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    $deleteResult = $dbManager->deleteAccount($deleteId);
    
    $message = $deleteResult['message'];
    $messageType = $deleteResult['success'] ? 'success' : 'error';
}

// Handle Edit Action
if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $editAccount = $dbManager->getAccountById($editId);
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        // Validate and sanitize inputs
        $name = trim($_POST['name']);
        $course = trim($_POST['course']);
        $email = trim($_POST['email']);
        $address = trim($_POST['address']);
        $average = floatval($_POST['average']); // Changed to floatval for decimal averages
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Securely hash password
        $age = intval($_POST['age']);
        $contact = trim($_POST['contact']); // Changed to trim to allow formatting
        
        // Validate inputs
        $errors = [];
        if (empty($name)) $errors[] = 'Full Name is required';
        if (empty($course)) $errors[] = 'Course is required';
        if (empty($email)) $errors[] = 'Email is required';
        if (strlen($_POST['password']) < 6) $errors[] = 'Password must be at least 6 characters long';
        
        if (empty($errors)) {
            // Attempt to insert account
            $result = $dbManager->insertAccount($name, $course, $email, $address, $average, $password, $age, $contact);
            
            $message = $result['message'];
            $messageType = $result['success'] ? 'success' : 'error';
        } else {
            $message = implode(', ', $errors);
            $messageType = 'error';
        }
    } elseif (isset($_POST['update'])) {
        // Process update
        $id = $_POST['id'];
        $name = trim($_POST['name']);
        $course = trim($_POST['course']);
        $email = trim($_POST['email']);
        $address = trim($_POST['address']);
        $average = floatval($_POST['average']);
        $age = intval($_POST['age']);
        $contact = trim($_POST['contact']);
        
        $result = $dbManager->updateAccount($id, $name, $course, $email, $address, $average, $age, $contact);
        
        $message = $result['message'];
        $messageType = $result['success'] ? 'success' : 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Account Management</title>
    <style>
            body {
            font-family: 'Arial', sans-serif;
            background-color:rgb(11, 11, 11);
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            padding: 20px;
            max-width: 1080px;
            margin: 0 auto;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
    
            text-align: center;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .content-wrapper {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        .signup-section, .table-section {
            background-color:rgb(33, 33, 44);
            padding: 20px;
            border-radius: 5px;
        }
        .signup-section h2, .table-section h2 {
            text-align: center;
            color: white;
            margin-bottom: 20px;
        }
        .signup-section form {
            display: flex;
            flex-direction: column;
        }
        .signup-section input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .signup-section input[type="submit"] {
            background-color: #d3ad7f;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .signup-section input[type="submit"]:hover {
            background-color: #d3ad7f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
            color: white;
        }
        th {
            background-color: #d3ad7f;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #000000;
        }
        .action-btn {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            margin-right: 5px;
        }
        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }
        .delete-btn:hover {
            background-color: #c0392b;
        }
        .edit-btn {
            background-color: #2ecc71;
            color: white;
        }
        .edit-btn:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php 
        // Display message if exists
        if (!empty($message)): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="content-wrapper">
            <div class="signup-section">
                <h2><?php echo isset($editAccount) ? 'Edit Account' : 'Create New Account'; ?></h2>
                <form method="POST" action="">
                    <?php if(isset($editAccount)): ?>
                        <input type="hidden" name="id" value="<?php echo $editAccount['id']; ?>">
                    <?php endif; ?>
                    
                    <input type="text" name="name" placeholder="Full Name" 
                           value="<?php echo isset($editAccount) ? htmlspecialchars($editAccount['name']) : ''; ?>" 
                           required>
                    
                    <input type="text" name="course" placeholder="Course" 
                           value="<?php echo isset($editAccount) ? htmlspecialchars($editAccount['course']) : ''; ?>" 
                           required>
                    
                    <input type="email" name="email" placeholder="Email" 
                           value="<?php echo isset($editAccount) ? htmlspecialchars($editAccount['email']) : ''; ?>" 
                           required>
                    
                    <input type="text" name="address" placeholder="Address" 
                           value="<?php echo isset($editAccount) ? htmlspecialchars($editAccount['address']) : ''; ?>" 
                           required>
                    
                    <input type="number" step="0.01" name="average" placeholder="Academic Average" 
                           value="<?php echo isset($editAccount) ? htmlspecialchars($editAccount['average']) : ''; ?>" 
                           required>
                    
                    <?php if(!isset($editAccount)): ?>
                        <input type="password" name="password" placeholder="Password" required>
                    <?php endif; ?>
                    
                    <input type="number" name="age" placeholder="Age" 
                           value="<?php echo isset($editAccount) ? htmlspecialchars($editAccount['age']) : ''; ?>" 
                           required>
                    
                    <input type="text" name="contact" placeholder="Contact Number" 
                           value="<?php echo isset($editAccount) ? htmlspecialchars($editAccount['contact']) : ''; ?>" 
                           required>
                    
                    <?php if(isset($editAccount)): ?>
                        <input type="submit" name="update" value="Update Account">
                    <?php else: ?>
                        <input type="submit" name="submit" value="Create Account">
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-section">
                <h2>Existing Accounts</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Course</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Academic Average</th>
                            <th>Age</th>
                            <th>Contact Number</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display all records
                        $result = $dbManager->getAllAccounts();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['course']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['average']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['contact']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                                echo "<td>
                                    <a href='?edit=" . $row['id'] . "' class='action-btn edit-btn'>Edit</a>
                                    <a href='?delete=" . $row['id'] . "' class='action-btn delete-btn' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>