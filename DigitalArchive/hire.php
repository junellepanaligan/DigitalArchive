<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$dbManager = new DatabaseManager();
$message = '';
$messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hire_lawyer'])) {
    $lawyer_id = $_POST['lawyer_id'];
    $payment_method = $_POST['payment_method'];
    
    // Get client information
    $client = $dbManager->getAccountById($_SESSION['user_id']);
    if (!$client) {
        $message = 'Error: Client information not found.';
        $messageType = 'error';
    } else {
        $client_id = $client['id']; // Get the actual client ID from the database
        $client_name = $client['name'];
        $client_email = $client['email'];
        
        // Get lawyer information
        $lawyer_name = '';
        switch ($lawyer_id) {
            case 1:
                $lawyer_name = "Atty. JIJI M. FRAKS";
                break;
            case 2:
                $lawyer_name = "Atty. JUNELLE N. FRUKHM";
                break;
            case 3:
                $lawyer_name = "Atty. ALBERTA S. EINSTEIN";
                break;
            case 4:
                $lawyer_name = "Atty. HENRY A. FRIKETS";
                break;
            case 5:
                $lawyer_name = "Atty. LOUIE N. NABS";
                break;
            case 6:
                $lawyer_name = "Atty. JUAN L. DEER";
                break;
        }
        
        // Process payment based on method
        switch ($payment_method) {
            case 'paymaya':
            case 'paypal':
            case 'gcash':
                // Create hiring record first
                $result = $dbManager->createHiringRecord($client_id, $client_name, $client_email, $lawyer_id, $lawyer_name, $payment_method);
                if ($result['success']) {
                    // Redirect to payment gateway
                    header("Location: process_payment.php?method=" . $payment_method . "&lawyer_id=" . $lawyer_id);
                    exit();
                } else {
                    $message = 'Error creating hiring record: ' . $result['message'];
                    $messageType = 'error';
                }
                break;
            case 'cash':
                // Create hiring record
                $result = $dbManager->createHiringRecord($client_id, $client_name, $client_email, $lawyer_id, $lawyer_name, 'cash');
                if ($result['success']) {
                    $message = 'Appointment created successfully! Please visit our office for cash payment.';
                    $messageType = 'success';
                } else {
                    $message = 'Error creating appointment: ' . $result['message'];
                    $messageType = 'error';
                }
                break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hire a Lawyer - PANALIGAN LAW OFFICE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        * {
            color: white;
        }
        .payment-modal, .bio-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            color: white;
        }
        .payment-content, .bio-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--black);
            padding: 2rem;
            border-radius: 5px;
            width: 90%;
            max-width: 500px;
        }
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin: 1rem 0;
        }
        .payment-method {
            padding: 1rem;
            border: 1px solid var(--main-color);
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
        }
        .payment-method:hover {
            background: var(--main-color);
        }
        .payment-method.selected {
            background: var(--main-color);
        }
        .close-modal {
            position: absolute;
            top: 1rem;
            right: 1rem;
            color: white;
            cursor: pointer;
        }
        .message {
            padding: 1rem;
            margin: 1rem 0;
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
        /* Bio Modal Styles */
        .bio-content {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            border: 2px solid var(--main-color);
        }
        .bio-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .bio-header img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 3px solid var(--main-color);
            margin-bottom: 1rem;
        }
        .bio-info {
            margin-bottom: 1rem;
        }
        .bio-info h3 {
            color: var(--main-color);
            margin-bottom: 0.5rem;
        }
        .bio-info p {
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }
        /* Theme-specific styles */
        .theme-1 .bio-content {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        }
        .theme-2 .bio-content {
            background: linear-gradient(135deg, #8e44ad 0%, #c0392b 100%);
        }
        .theme-3 .bio-content {
            background: linear-gradient(135deg, #16a085 0%, #2c3e50 100%);
        }
        .theme-4 .bio-content {
            background: linear-gradient(135deg, #d35400 0%, #c0392b 100%);
        }
        .theme-5 .bio-content {
            background: linear-gradient(135deg, #27ae60 0%, #2c3e50 100%);
        }
        .theme-6 .bio-content {
            background: linear-gradient(135deg, #2980b9 0%, #2c3e50 100%);
        }
    </style>
</head>
<body>
<section class="menu" id="menu">
    <h1 class="heading"> our <span>TEAM</span> </h1>

    <?php if ($message): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="box-container">
        <div class="box">
            <img src="images/JIJI.jpg" alt="">
            <h3>Atty. JIJI M. FRAKS</h3>
            <div class="price">associate lawyer</div>
            <button class="btn view-bio-btn" data-lawyer-id="1" data-theme="theme-1">View Bio</button>
            <button class="btn hire-btn" data-lawyer-id="1">Hire</button>
        </div>

        <div class="box">
            <img src="images/JUNELLE.jpg" alt="">
            <h3>Atty. JUNELLE N. FRUKHM</h3>
            <div class="price">Senior Lawyer</div>
            <button class="btn view-bio-btn" data-lawyer-id="2" data-theme="theme-2">View Bio</button>
            <button class="btn hire-btn" data-lawyer-id="2">Hire</button>
        </div>

        <div class="box">
            <img src="images/ALBERT.png" alt="">
            <h3>Atty. ALBERTA S. EINSTEIN</h3>
            <div class="price">Associate Lawyer</div>
            <button class="btn view-bio-btn" data-lawyer-id="3" data-theme="theme-3">View Bio</button>
            <button class="btn hire-btn" data-lawyer-id="3">Hire</button>
        </div>

        <div class="box">
            <img src="images/HENRY.jpg" alt="">
            <h3>Atty. HENRY A. FRIKETS</h3>
            <div class="price">Associate Lawyer</div>
            <button class="btn view-bio-btn" data-lawyer-id="4" data-theme="theme-4">View Bio</button>
            <button class="btn hire-btn" data-lawyer-id="4">Hire</button>
        </div>

        <div class="box">
            <img src="images/LOUIE.jpg" alt="">
            <h3>Atty. LOUIE N. NABS</h3>
            <div class="price">Junior Lawyer</div>
            <button class="btn view-bio-btn" data-lawyer-id="5" data-theme="theme-5">View Bio</button>
            <button class="btn hire-btn" data-lawyer-id="5">Hire</button>
        </div>

        <div class="box">
            <img src="images/RAFAEL.png" alt="">
            <h3>Atty. JUAN L. DEER</h3>
            <div class="price">Associate Lawyer</div>
            <button class="btn view-bio-btn" data-lawyer-id="6" data-theme="theme-6">View Bio</button>
            <button class="btn hire-btn" data-lawyer-id="6">Hire</button>
        </div>
    </div>
</section>

<!-- Payment Modal -->
<div class="payment-modal" id="paymentModal">
    <div class="payment-content">
        <span class="close-modal">&times;</span>
        <h3>Select Payment Method</h3>
        <form method="POST" action="">
            <input type="hidden" name="lawyer_id" id="selectedLawyerId">
            <div class="payment-methods">
                <label class="payment-method">
                    <input type="radio" name="payment_method" value="paymaya" required>
                    <img src="images/maya.jpg" alt="PayMaya" style="width: 100px;">
                </label>
                <label class="payment-method">
                    <input type="radio" name="payment_method" value="paypal" required>
                    <img src="images/paypal.png" alt="PayPal" style="width: 100px;">
                </label>
                <label class="payment-method">
                    <input type="radio" name="payment_method" value="gcash" required>
                    <img src="images/gcash.jpg" alt="GCash" style="width: 100px;">
                </label>
                <label class="payment-method">
                    <input type="radio" name="payment_method" value="cash" required>
                    <img src="images/cash.jpg" alt="GCash" style="width: 65px;">
                </label>
            </div>
            <button type="submit" name="hire_lawyer" class="btn">Proceed to Payment</button>
        </form>
    </div>
</div>

<!-- Bio Modals -->
<div class="bio-modal" id="bioModal">
    <div class="bio-content">
        <span class="close-modal">&times;</span>
        <div class="bio-header">
            <img id="bioImage" src="" alt="Lawyer Photo">
            <h2 id="bioName"></h2>
            <p id="bioTitle"></p>
        </div>
        <div class="bio-info">
            <h3>Education</h3>
            <p id="bioEducation"></p>
            
            <h3>Specialization</h3>
            <p id="bioSpecialization"></p>
            
            <h3>Experience</h3>
            <p id="bioExperience"></p>
            
            <h3>Achievements</h3>
            <p id="bioAchievements"></p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentModal = document.getElementById('paymentModal');
    const bioModal = document.getElementById('bioModal');
    const closeBtns = document.querySelectorAll('.close-modal');
    const hireBtns = document.querySelectorAll('.hire-btn');
    const viewBioBtns = document.querySelectorAll('.view-bio-btn');
    const lawyerIdInput = document.getElementById('selectedLawyerId');

    // Lawyer bio data
    const lawyerBios = {
        1: {
            name: "Atty. JIJI M. FRAKS",
            title: "Associate Lawyer",
            education: "Juris Doctor, Harvard Law School (2015)\nBachelor of Laws, University of the Philippines (2011)",
            specialization: "Corporate Law, Intellectual Property",
            experience: "8 years of experience in corporate law and intellectual property rights. Former legal counsel for Fortune 500 companies.",
            achievements: "Recipient of the 2020 Legal Excellence Award\nPublished author in International Law Journal",
            image: "images/JIJI.jpg"
        },
        2: {
            name: "Atty. JUNELLE N. FRUKHM",
            title: "Senior Lawyer",
            education: "Juris Doctor, Yale Law School (2010)\nBachelor of Laws, Ateneo de Manila University (2006)",
            specialization: "Criminal Law, Family Law",
            experience: "12 years of experience in criminal defense and family law. Successfully handled over 500 cases.",
            achievements: "Named Top Criminal Defense Lawyer 2021\nPro Bono Service Award 2019",
            image: "images/JUNELLE.jpg"
        },
        3: {
            name: "Atty. ALBERTA S. EINSTEIN",
            title: "Associate Lawyer",
            education: "Juris Doctor, Stanford Law School (2017)\nBachelor of Laws, University of California (2013)",
            specialization: "Environmental Law, Real Estate",
            experience: "6 years of experience in environmental law and real estate transactions. Expert in sustainable development cases.",
            achievements: "Environmental Law Excellence Award 2022\nGreen Legal Advocate of the Year 2021",
            image: "images/ALBERT.png"
        },
        4: {
            name: "Atty. HENRY A. FRIKETS",
            title: "Associate Lawyer",
            education: "Juris Doctor, Columbia Law School (2016)\nBachelor of Laws, New York University (2012)",
            specialization: "Tax Law, Business Law",
            experience: "7 years of experience in tax law and business regulations. Specialized in international tax planning.",
            achievements: "Tax Law Innovator Award 2021\nBusiness Law Excellence Award 2020",
            image: "images/HENRY.jpg"
        },
        5: {
            name: "Atty. LOUIE N. NABS",
            title: "Junior Lawyer",
            education: "Juris Doctor, University of Michigan Law School (2019)\nBachelor of Laws, University of Chicago (2015)",
            specialization: "Immigration Law, Human Rights",
            experience: "4 years of experience in immigration law and human rights cases. Focused on refugee rights.",
            achievements: "Human Rights Advocate Award 2022\nYoung Lawyer of the Year 2021",
            image: "images/LOUIE.jpg"
        },
        6: {
            name: "Atty. JUAN L. DEER",
            title: "Associate Lawyer",
            education: "Juris Doctor, Georgetown Law (2018)\nBachelor of Laws, Boston University (2014)",
            specialization: "Labor Law, Employment Law",
            experience: "5 years of experience in labor and employment law. Expert in workplace discrimination cases.",
            achievements: "Labor Law Excellence Award 2022\nEmployment Rights Champion 2021",
            image: "images/RAFAEL.png"
        }
    };

    // Handle hire buttons
    hireBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const lawyerId = this.getAttribute('data-lawyer-id');
            lawyerIdInput.value = lawyerId;
            paymentModal.style.display = 'block';
        });
    });

    // Handle view bio buttons
    viewBioBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const lawyerId = this.getAttribute('data-lawyer-id');
            const theme = this.getAttribute('data-theme');
            const bio = lawyerBios[lawyerId];
            
            // Update bio modal content
            document.getElementById('bioImage').src = bio.image;
            document.getElementById('bioName').textContent = bio.name;
            document.getElementById('bioTitle').textContent = bio.title;
            document.getElementById('bioEducation').textContent = bio.education;
            document.getElementById('bioSpecialization').textContent = bio.specialization;
            document.getElementById('bioExperience').textContent = bio.experience;
            document.getElementById('bioAchievements').textContent = bio.achievements;
            
            // Apply theme
            document.querySelector('.bio-content').className = 'bio-content ' + theme;
            
            bioModal.style.display = 'block';
        });
    });

    // Handle close buttons
    closeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            paymentModal.style.display = 'none';
            bioModal.style.display = 'none';
        });
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === paymentModal) {
            paymentModal.style.display = 'none';
        }
        if (event.target === bioModal) {
            bioModal.style.display = 'none';
        }
    });
});
</script>
</body>
</html>