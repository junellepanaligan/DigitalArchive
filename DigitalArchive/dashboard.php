<?php
session_start();
require_once 'database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$dbManager = new DatabaseManager();
$hiring_records = $dbManager->getAllHiringRecords();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiring Records Dashboard - PANALIGAN LAW OFFICE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .status-active {
            color: #28a745;
        }
        .status-inactive {
            color: #dc3545;
        }
        .no-records {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hiring Records Dashboard</h1>
        
        <table>
            <thead>
                <tr>
                    <th>Client ID</th>
                    <th>Client Name</th>
                    <th>Client Email</th>
                    <th>Lawyer</th>
                    <th>Hire Date</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($hiring_records && $hiring_records->num_rows > 0): ?>
                    <?php while ($record = $hiring_records->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['client_id']); ?></td>
                            <td><?php echo htmlspecialchars($record['client_name']); ?></td>
                            <td><?php echo htmlspecialchars($record['client_email']); ?></td>
                            <td><?php echo htmlspecialchars($record['lawyer_name']); ?></td>
                            <td><?php echo date('F j, Y g:i A', strtotime($record['hire_date'])); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($record['payment_method'])); ?></td>
                            <td class="status-<?php echo $record['status']; ?>">
                                <?php echo htmlspecialchars(ucfirst($record['status'])); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="no-records">No hiring records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html> 