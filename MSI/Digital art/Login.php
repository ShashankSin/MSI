<?php
include('db.php');
session_start();

// Initialize variables
$login_error = '';
$login_success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = trim($_POST['user_email']);
    $user_password = trim($_POST['user_password']);

    // Sanitize inputs
    $user_email = htmlspecialchars($user_email);

    // Basic validation
    if (empty($user_email) || empty($user_password)) {
        $login_error = 'Both email and password are required.';
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $login_error = 'Invalid email format.';
    } else {
        // Query to check credentials
        $query = "SELECT * FROM User WHERE user_email='$user_email'";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($user_password, $user['user_password'])) {
                $_SESSION['user_id'] = $user['u_id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['user_phone'] = $user['user_phone'];
                header("Location: loggedin.php"); // Redirect to a dashboard or profile page
                exit();
            } else {
                $login_error = 'Invalid email or password.';
            }
        } else {
            $login_error = 'Invalid email or password.';
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
                     <li><a href="cart.php">Cart</a></li>
                     <li><a href="#"><img src="images/search-icon.png"></a></li>
                  </ul>
               </div>
            </div>
         </div>
         <!-- banner section end -->
      </div>
      
    <div class="">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <h2 class="text-center">Login</h2>
                <?php if ($login_error): ?>
                    <div class="alert alert-danger"><?php echo $login_error; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="user_email">Email address</label>
                        <input type="email" class="form-control" id="user_email" name="user_email" value="<?php echo isset($user_email) ? htmlspecialchars($user_email) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="user_password">Password</label>
                        <input type="password" class="form-control" id="user_password" name="user_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p>
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
