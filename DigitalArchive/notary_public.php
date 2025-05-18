<?php
require_once 'database.php';
session_start();

// Initialize database connection
$db = new DatabaseManager();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
        $user_id = $_SESSION['user_id'];
        $user_name = $_SESSION['user_name'];
        $document_type = $_POST['document_type'];
        $document_description = $_POST['document_description'];
        $appointment_date = $_POST['appointment_date'];
        $appointment_time = $_POST['appointment_time'];
        $additional_notes = $_POST['additional_notes'];
        
        // Handle file upload
        $document_file = '';
        if (isset($_FILES['document_file']) && $_FILES['document_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['document_file']['name'], PATHINFO_EXTENSION));
            if ($file_extension === 'pdf') {
                $new_filename = uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['document_file']['tmp_name'], $upload_path)) {
                    $document_file = $upload_path;
                }
            }
        }
        
        // Save to database
        if ($db->saveNotaryRequest($user_id, $user_name, $document_type, $document_description, $document_file, $appointment_date, $appointment_time, $additional_notes)) {
            $success_message = "Notary request submitted successfully!";
        } else {
            $error_message = "Error submitting notary request. Please try again.";
        }
    } else {
        $error_message = "Please login to submit a notary request.";
    }
}

// Get all notary requests
$notaryRequests = $db->getNotaryRequests();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notary Public Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="fonts/remixicon.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .table-container {
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .page-header {
            background-color: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .form-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .alert {
            margin: 20px auto;
            max-width: 800px;
        }
    </style>
</head>
<body>
    <!-- header section starts -->
    <header class="header">
        <a href="index.php" class="logo">
            <img src="images/PANALIGAN LAW OFFICE.png" alt="">
        </a>

        <nav class="navbar">
            <a href="index.php#home">home</a>
            <a href="index.php#about">about</a>
            <a href="index.php#menu">gallery</a>
            <a href="index.php#products">product/services</a>
            <a href="index.php#review">testimonial</a>
            <a href="index.php#contact">contact</a>
            <a href="index.php#blogs">news and events</a>
        </nav>

        <?php if (isset($_SESSION['user_name'])): ?>
            <div class="user-info" style="color: white; font-size: 16px; border: 1px solid white; padding: 10px; margin-right: 10px;">
                Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                <a href="logout.php" style="color: white; margin-left: 10px;">
                    <i class="ri-arrow-left-down-line"></i>
                </a>
            </div>
        <?php endif; ?>
    </header>
    <!-- header section ends -->

    <div class="container">
        <div class="page-header">
            <h1 class="text-center">Notary Public Services</h1>
            <p class="text-center text-muted">Submit and manage notary service requests</p>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Notary Request Form -->
        <div class="form-container">
            <form action="notary_public.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="document_type" class="form-label">Type of Document:</label>
                    <select name="document_type" id="document_type" class="form-select" required>
                        <option value="">Select Document Type</option>
                        <option value="affidavit">Affidavit</option>
                        <option value="deed">Deed</option>
                        <option value="power_of_attorney">Power of Attorney</option>
                        <option value="certification">Certification</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="document_description" class="form-label">Document Description:</label>
                    <textarea name="document_description" id="document_description" class="form-control" required rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="document_file" class="form-label">Upload Document (PDF only):</label>
                    <input type="file" name="document_file" id="document_file" class="form-control" accept=".pdf" required>
                </div>

                <div class="mb-3">
                    <label for="appointment_date" class="form-label">Preferred Appointment Date:</label>
                    <input type="date" name="appointment_date" id="appointment_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="appointment_time" class="form-label">Preferred Appointment Time:</label>
                    <input type="time" name="appointment_time" id="appointment_time" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="additional_notes" class="form-label">Additional Notes:</label>
                    <textarea name="additional_notes" id="additional_notes" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Submit Notary Request</button>
            </form>
        </div>

        <!-- Notary Requests Table -->
        <div class="table-container">
            <h2 class="text-center mb-4">Notary Requests</h2>
            
            <!-- Sort Controls -->
            <div class="text-end mb-3">
                <select class="form-select form-select-sm" style="width: auto; display: inline-block;" onchange="updateSort(this.value)">
                    <option value="created_at">Date Created</option>
                    <option value="user_name">User Name</option>
                    <option value="document_type">Document Type</option>
                    <option value="appointment_date">Appointment Date</option>
                    <option value="appointment_time">Appointment Time</option>
                    <option value="id">ID</option>
                </select>
    
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>User Name</th>
                            <th>Document Type</th>
                            <th>Description</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Additional Notes</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($notaryRequests && $notaryRequests->num_rows > 0) {
                            while ($row = $notaryRequests->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['document_type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['document_description']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['appointment_date']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['appointment_time']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['additional_notes']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>No notary requests found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- footer section ends -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        function updateSort(column) {
            const table = document.querySelector('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            const columnIndex = {
                'id': 0,
                'user_name': 1,
                'document_type': 2,
                'appointment_date': 4,
                'appointment_time': 5,
                'created_at': 7
            }[column];
            
            rows.sort((a, b) => {
                let valueA = a.cells[columnIndex].textContent.trim();
                let valueB = b.cells[columnIndex].textContent.trim();
                
                // Handle date/time columns
                if (['created_at', 'appointment_date', 'appointment_time'].includes(column)) {
                    valueA = new Date(valueA);
                    valueB = new Date(valueB);
                }
                
                // Default to ascending order
                if (valueA < valueB) return -1;
                if (valueA > valueB) return 1;
                return 0;
            });
            
            // Clear and re-append sorted rows
            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
        }
    </script>
</body>
</html> 