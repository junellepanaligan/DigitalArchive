<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$dbManager = new DatabaseManager();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Complete - PANALIGAN LAW OFFICE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .payment-complete {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--black);
            padding: 2rem;
        }
        .payment-box {
            background: var(--bg);
            padding: 3rem;
            border-radius: 5px;
            text-align: center;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }
        .payment-box i {
            font-size: 5rem;
            color: #4CAF50;
            margin-bottom: 2rem;
        }
        .payment-box h1 {
            color: var(--main-color);
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .payment-box p {
            color: var(--white);
            font-size: 1.6rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .payment-box .btn {
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <section class="payment-complete">
        <div class="payment-box">
            <i class="fas fa-check-circle"></i>
            <h1>Payment Successful!</h1>
            <p>Thank you for your payment. Please check your Gmail for more information about your appointment and payment details.</p>
            <p>We have sent you a confirmation email with all the necessary information.</p>
            <a href="index.php" class="btn">Return to Home</a>
        </div>
    </section>
</body>
</html> 