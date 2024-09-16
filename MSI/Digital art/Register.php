<?php 
include('db.php');
session_start();

// Initialize variables
$registration_error = '';
$registration_success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = trim($_POST['user_name']);
    $user_email = trim($_POST['user_email']);
    $user_password = trim($_POST['user_password']);
    $user_phone = trim($_POST['user_phone']);

    // Sanitize inputs
    $user_name = htmlspecialchars($user_name);
    $user_email = htmlspecialchars($user_email);
    $user_phone = htmlspecialchars($user_phone);

    // Basic validation
    if (empty($user_name) || empty($user_email) || empty($user_password) || empty($user_phone)) {
        $registration_error = 'All fields are required.';
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $registration_error = 'Invalid email format.';
    } elseif (strlen($user_password) < 6) {
        $registration_error = 'Password must be at least 6 characters long.';
    } else {
        // Check if email already exists
        $email_check_query = "SELECT * FROM User WHERE user_email='$user_email'";
        $email_check_result = $conn->query($email_check_query);

        if ($email_check_result->num_rows > 0) {
            $registration_error = 'Email is already registered.';
        } else {
            // Hash password
            $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

            // Insert user into the database
            $query = "INSERT INTO User (user_name, user_email, user_password, user_phone) VALUES ('$user_name', '$user_email', '$hashed_password', '$user_phone')";
            
            if ($conn->query($query) === TRUE) {
                $registration_success = 'Registration successful. You can now <a href="login.php">login</a>.';
            } else {
                $registration_error = 'Error: ' . $conn->error;
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>Uoni</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
      <!-- style css -->
      <link rel="stylesheet" type="text/css" href="css/style.css">
      <!-- Responsive-->
      <link rel="stylesheet" href="css/responsive.css">
      <!-- fevicon -->
      <link rel="icon" href="images/fevicon.png" type="image/gif" />
      <!-- Scrollbar Custom CSS -->
      <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
      <!-- Tweaks for older IEs-->
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
      <!-- fonts -->
      <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
      <!-- owl stylesheets --> 
      <link rel="stylesheet" href="css/owl.carousel.min.css">
      <link rel="stylesheet" href="css/owl.theme.default.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
      <style>
         body{
            position: relative;
            height: 100vh;
         }
         .footer_section{
            position: absolute;
            bottom: 0;
            left: 0;
         }
      </style>
   </head>
   <body>
      <!-- header section start -->
      <div class="header_section" style="background-image: none;">
         <div class="header_main">
            <div class="mobile_menu">
               <nav class="navbar navbar-expand-lg navbar-light bg-light">
                  <div class="logo_mobile"><a href="index.php"><img src="images/logo.png"></a></div>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarNav">
                     <ul class="navbar-nav">
                        <li class="nav-item active">
                           <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="about.php">About</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="watchs.php">Watchs</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link " href="testimonial.php">Testimonial</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link " href="contact.php">Contact</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link " href="Login.php">Login</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link " href="#"><img src="images/search-icon.png"></a>
                        </li>
                     </ul>
                  </div>
               </nav>
            </div>
            <div class="container-fluid">
               <div class="logo"><a href="index.php"><img src="images/logo.png"></a></div>
               <div class="menu_main">
                  <ul>
                     <li class="active"><a href="index.php">Home</a></li>
                     <li><a href="about.php">About</a></li>
                     <li><a href="watchs.php">Watchs</a></li>
                     <li><a href="testimonial.php">Testimonial</a></li>
                     <li><a href="contact.php">Contact us</a></li>
                     <li><a href="#"><img src="images/search-icon.png"></a></li>
                  </ul>
               </div>
            </div>
         </div>
         <!-- banner section end -->
      </div>
      
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <h2 class="text-center">Register</h2>
                <?php if ($registration_error): ?>
                    <div class="alert alert-danger"><?php echo $registration_error; ?></div>
                <?php endif; ?>
                <?php if ($registration_success): ?>
                    <div class="alert alert-success"><?php echo $registration_success; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="user_name">Name</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo isset($user_name) ? htmlspecialchars($user_name) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="user_email">Email address</label>
                        <input type="email" class="form-control" id="user_email" name="user_email" value="<?php echo isset($user_email) ? htmlspecialchars($user_email) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="user_password">Password</label>
                        <input type="password" class="form-control" id="user_password" name="user_password" required>
                    </div>
                    <div class="form-group">
                        <label for="user_phone">Phone Number</label>
                        <input type="text" class="form-control" id="user_phone" name="user_phone" value="<?php echo isset($user_phone) ? htmlspecialchars($user_phone) : ''; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer_section layout_padding">
         <div class="container">
            <h3 class="follow_text">Follow Now</h3>
            <div class="social_icon">
               <ul>
                  <li><a href="#"><img src="images/fb-icon.png"></a></li>
                  <li><a href="#"><img src="images/twitter-icon.png"></a></li>
                  <li><a href="#"><img src="images/linkedin-icon.png"></a></li>
                  <li><a href="#"><img src="images/instagram-icon.png"></a></li>
                  <li><a href="#"><img src="images/youtub-icon.png"></a></li>
               </ul>
            </div>
            <div class="location_main">
               <div class="location_left">
                  <div class="call_text"><a href="#"><img src="images/map-icon.png"><span class="call_padding">Location</span></a></div>
               </div>
               <div class="location_middle">
                  <div class="call_text"><a href="#"><img src="images/mail-icon.png"><span class="call_padding">demo@gmail.com</span></a></div>
               </div>
               <div class="location_right">
                  <div class="call_text"><a href="#"><img src="images/call-icon.png"><span class="call_padding">Call +01 1234567890</span></a></div>
               </div>
            </div>
         </div>
      </div>
      <!-- footer section end -->
      <!-- copyright section end -->
      <!-- Javascript files-->
      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/jquery-3.0.0.min.js"></script>
      <script src="js/plugin.js"></script>
      <!-- sidebar -->
      <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="js/custom.js"></script>
      <!-- javascript --> 
      <script src="js/owl.carousel.js"></script>
      <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>    
   </body>
</html>
