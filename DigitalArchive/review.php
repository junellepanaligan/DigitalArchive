<?php
session_start();
require_once 'database.php';
$dbManager = new DatabaseManager();

$message = '';
$messageType = '';

// Handle review submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $rating = intval($_POST['rating']);
    $feedback = trim($_POST['feedback']);
    
    if ($rating >= 1 && $rating <= 5 && !empty($feedback)) {
        $result = $dbManager->insertFeedback($_SESSION['user_name'], $feedback, $rating);
        if ($result['success']) {
            // Redirect to prevent form resubmission
            header("Location: review.php?success=1");
            exit();
        } else {
            $message = $result['message'];
            $messageType = 'error';
        }
    } else {
        $message = 'Please provide a valid rating and feedback';
        $messageType = 'error';
    }
}

// Check for success message in URL
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = 'Review submitted successfully!';
    $messageType = 'success';
}

// Get recent reviews
$reviews = $dbManager->getRecentFeedbacks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews - PANALIGAN LAW OFFICE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }
        .rating input {
            display: none;
        }
        .rating label {
            cursor: pointer;
            font-size: 2rem;
            color: #ccc;
            margin: 0 5px;
        }
        .rating label:hover,
        .rating label:hover ~ label,
        .rating input:checked ~ label {
            color: #d3ad7f;
        }
        .review-form {
            background: var(--black);
            padding: 2rem;
            border-radius: 5px;
            margin-bottom: 2rem;
        }
        .review-form textarea {
            width: 100%;
            padding: 1rem;
            margin: 1rem 0;
            background: var(--bg);
            border: var(--border);
            color: #fff;
            font-size: 1.6rem;
            resize: vertical;
            min-height: 100px;
        }
        .message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
        }
        h3{
            color: white;
            align-items: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <section class="review" id="review">
        <h1 class="heading">Customer <span>Reviews</span></h1>

        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="review-form">
                <h3>Write a Review</h3>
                <form method="POST" action="">
                    <div class="rating">
                        <input type="radio" id="star5" name="rating" value="5" required>
                        <label for="star5"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star4" name="rating" value="4">
                        <label for="star4"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1"><i class="fas fa-star"></i></label>
                    </div>
                    <textarea name="feedback" placeholder="Write your review here..." required></textarea>
                    <button type="submit" class="btn">Submit Review</button>
                </form>
            </div>
        <?php else: ?>
            <div class="message">
                Please <a href="login.php">login</a> to write a review.
            </div>
        <?php endif; ?>

        <div class="box-container">
            <?php
            if ($reviews && $reviews->num_rows > 0) {
                while ($review = $reviews->fetch_assoc()) {
                    echo '<div class="box">';
                    echo '<img src="images/quote-img.png" alt="" class="quote">';
                    echo '<p>' . htmlspecialchars($review['feedback']) . '</p>';
                    echo '<h3>' . htmlspecialchars($review['username']) . '</h3>';
                    echo '<div class="stars">';
                    for ($i = 0; $i < $review['rating']; $i++) {
                        echo '<i class="fas fa-star"></i>';
                    }
                    for ($i = $review['rating']; $i < 5; $i++) {
                        echo '<i class="far fa-star"></i>';
                    }
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="box">';
                echo '<p>No reviews yet. Be the first to review!</p>';
                echo '</div>';
            }
            ?>
        </div>
    </section>
</body>
</html> 