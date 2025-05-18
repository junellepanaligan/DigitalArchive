<!DOCTYPE html>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'database.php';
$dbManager = new DatabaseManager();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PANALIGAN LAW OFFICE</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="fonts/remixicon.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
<!-- header section starts  -->

<header class="header">

    <a href="#" class="logo">
        <img src="images/PANALIGAN LAW OFFICE.png" alt="">
    </a>

    <nav class="navbar">
        <a href="#home">home</a>
        <a href="#about">about</a>
        <a href="#menu">gallery</a>
        <a href="#products">product/services</a>
        <a href="#review">testimonial</a>
        <a href="#contact">contact</a>
        <a href="#blogs">news and events</a>
        <a href="student_tables.php">Student Record</a>
    </nav>

    <div class="icons">
        <div class="fas fa-search" id="search-btn"></div>
        <div class="fas fa-bars" id="menu-btn"></div>
    </div>

    <?php if (isset($_SESSION['user_name'])): ?>
        <div class="user-info" style="color: white; font-size: 16px; border: 1px solid white; padding: 10px; margin-right: 10px;">
            Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
            <a href="logout.php" style="color: white; margin-left: 10px;">
            <i class="ri-arrow-left-down-line"></i>
            </a>
        </div>
    <?php else: ?>
        <div class="login-btn" style="color: white; font-size: 16px; border: 1px solid white; padding: 10px;">
            <a href="login.php" style="color: white;">Login</a>
        </div>
    <?php endif; ?>

    <div class="search-form">
        <input type="search" id="search-box" placeholder="search here...">
        <label for="search-box" class="fas fa-search"></label>
    </div>
</header>

<!-- header section ends -->

<!-- home section starts  -->

<section class="home" id="home">

    <div class="content">
        <h3>PANALIGAN LAW OFFICE</h3>
        <p>PANALIGAN LAW OFFICE provides expert legal services with a commitment to integrity,professionalism, and client-focused solutions.</p>
        <a href="#contact" class="btn">APPOINT now</a>
        
    </div>

</section>

<!-- home section ends -->

<!-- about section starts  -->

<section class="about" id="about">

    <h1 class="heading"> <span>about</span> us </h1>

    <div class="row">

        <div class="image">
            <img src="images/PANALIGAN LAW OFFICE.png" alt="">
        </div>

        <div class="content">
            <h3>PANALIGAN LAW OFFICE</h3>
            <p>We are dedicated to providing exeptional legal services tailored to our clients' unique needs with a strong commitment to integrity, professionalism, and excellence and we work diligently to achieve the best possible outcomes.</p>
            <p>Our firm takes pride in delivering strategic, results-driven legal solutions while maintaining a client-centered approach. Wether you need legal representation, guidance, or advocacy, PANALIGAN LAW OFFICE is here to protect your rights and interests with expertise and dedication.</p>
            <a href="#" class="btn">learn more</a>
        </div>

    </div>

</section>

<!-- about section ends -->

<!-- menu section starts  -->

<section class="menu" id="menu">

    <h1 class="heading"> our <span>TEAM</span> </h1>

    <div class="box-container">

        <div class="box">
            <img src="images/JIJI.jpg" alt="">
            <h3>Atty. JIJI M. FRAKS</h3>
            <div class="price">associate lawyer</div>
            <a href="#" class="btn">Atty. FRAKS Info.</a>
        </div>

        <div class="box">
            <img src="images/JUNELLE.jpg" alt="">
            <h3>Atty. JUNELLE N. FRUKHM</h3>
            <div class="price">Senior Lawyer</div>
            <a href="#" class="btn">Atty. FRUKHM Info.</a>
        </div>

        <div class="box">
            <img src="images/ALBERT.png" alt="">
            <h3>Atty. ALBERTA S. EINSTEIN</h3>
            <div class="price">Associate Lawyer</div>
            <a href="#" class="btn">Atty. EINSTEIN Info.</a>
        </div>

        <div class="box">
            <img src="images/HENRY.jpg" alt="">
            <h3>Atty. HENRY A. FRIKETS</h3>
            <div class="price">Associate Lawyer</div>
            <a href="#" class="btn">Atty. FRIKETS Info.</a>
        </div>

        <div class="box">
            <img src="images/LOUIE.jpg" alt="">
            <h3>Atty. LOUIE N. NABS</h3>
            <div class="price">Junior Lawyer</div>
            <a href="#" class="btn">Atty. NABS Info.</a>
        </div>

        <div class="box">
            <img src="images/RAFAEL.png" alt="">
            <h3>Atty. JUAN L. DEER</h3>
            <div class="price">Associate Lawyer</div>
            <a href="#" class="btn">Atty. DEER Info.</a>
        </div>

    </div>

</section>

<!-- menu section ends -->

<section class="products" id="products">

    <h1 class="heading"> our <span>SERVICES</span> </h1>

    <div class="box-container">

        <div class="box">
            <div class="icons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="notary_public.php" class="fas fa-user"></a>
                <?php else: ?>
                    <a href="login.php" class="fas fa-user"></a>
                <?php endif; ?>
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-eye"></a>
            </div>
            <div class="image">
                <img src="images/NOTARY.jpg" alt="">
            </div>x
            <div class="content">
                <h3>NOTARY PUBLIC</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <p>View and manage notary service requests</p>
            </div>
        </div>

        <div class="box">
            <div class="icons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="hire.php" class="fas fa-user"></a>
                <?php else: ?>
                    <a href="login.php" class="fas fa-user"></a>
                <?php endif; ?>
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-eye"></a>
            </div>
            <div class="image">
                <img src="images/LADY JUSTICE.jpg" alt="">
            </div>
            <div class="content">
                <h3>CIVIL & CRIMINAL CASES</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
             
            </div>
        </div>

        <div class="box">
            <div class="icons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="hire.php" class="fas fa-user"></a>
                <?php else: ?>
                    <a href="login.php" class="fas fa-user"></a>
                <?php endif; ?>
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-eye"></a>
            </div>
            <div class="image">
                <img src="images/LEGAL ADVICE.png" alt="">
            </div>
            <div class="content">
                <h3>LEGAL ADVICE</h3>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
               
            </div>
        </div>

    </div>

</section>

<!-- review section starts  -->

<section class="review" id="review">

    <h1 class="heading"> customer's <span>review</span> </h1>

    <div class="box-container">
        <?php
        $reviews = $dbManager->getRecentFeedbacks();
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
        }
        ?>
    </div>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="review.php" class="btn">Write a Review</a>
    </div>

</section>

<!-- review section ends -->

<!-- contact section starts  -->

<section class="contact" id="contact">

    <h1 class="heading"> <span>contact</span> us </h1>

    <div class="row">

        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15668.42540955282!2d121.99982373325145!3d10.955338394061526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33afb59963be4ea7%3A0x686c4d44eb04f1d5!2sCarit-an%2C%20Patnongon%2C%20Antique!5e0!3m2!1sen!2sph!4v1742911807966!5m2!1sen!2sph" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

        <form action="">
            <h3>get in touch</h3>
            <div class="inputBox">
                <span class="fas fa-user"></span>
                <input type="text" placeholder="name">
            </div>
            <div class="inputBox">
                <span class="fas fa-envelope"></span>
                <input type="email" placeholder="email">
            </div>
            <div class="inputBox">
                <span class="fas fa-phone"></span>
                <input type="number" placeholder="number">
            </div>
            <input type="submit" value="contact now" class="btn">
        </form>

    </div>

</section>

<!-- contact section ends -->

<!-- blogs section starts  -->

<section class="blogs" id="blogs">

    <h1 class="heading"> NEWS AND <span>EVENTS</span> </h1>

    <div class="box-container">

        <div class="box">
            <div class="image">
                <img src="images/NEJI.jpg" alt="">
            </div>
            <div class="content">
                <a href="#" class="title">CRIMINAL CASE NO. 9165</a>
                <span>NEJI THE GREAT</span>
                <p>MR. THE GREAT was found "Guilty" of the crime by using the illegal drugs under R.A. 9165</p>
                <a href="#" class="btn">read more</a>
            </div>
        </div>

        <div class="box">
            <div class="image">
                <img src="images/LAWYERS.jpg" alt="">
            </div>
            <div class="content">
                <a href="#" class="title">CIVIL AND CRIMINAL CASE LAWYER'S EXPERT</a>
                <span>March 2025 Civil and Criminal Case Closed</span>
                <p>Atty. Jiji Friks, Atty. Alberta Einstein and Atty. Rene Descartes Close the Criminal Cased Specifically the Criminal Case R.A 9165</p>
                <a href="#" class="btn">read more</a>
            </div>
        </div>

        <div class="box">
            <div class="image">
                <img src="images/DANIEL.jpg" alt="">
            </div>
            <div class="content">
                <a href="#" class="title">CIVIL CASE FOR ESTAFA</a>
                <span>DANIEL GORAA</span>
                <p>Article 315 of the Revised Penal Code (RPC) MR. GORAA HAS BEEN CHARGED WITH ESTAFA CASE</p>
                <a href="#" class="btn">read more</a>
            </div>
        </div>

    </div>

</section>

<!-- blogs section ends -->

<!-- footer section starts  -->

<section class="footer">

    <div class="share">
        <a href="#" class="fab fa-facebook-f"></a>
        <a href="#" class="fab fa-twitter"></a>
        <a href="#" class="fab fa-instagram"></a>
        <a href="#" class="fab fa-linkedin"></a>
        <a href="#" class="fab fa-pinterest"></a>
    </div>

    <div class="links">
        <a href="#">home</a>
        <a href="#">about</a>
        <a href="#">gallery</a>
        <a href="#">product/services</a>
        <a href="#">testimonial</a>
        <a href="#">contact</a>
        <a href="#">news and events</a>
    </div>

    <div class="credit">panaligan <span>law office</span> | all rights reserved</div>

</section>

<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>