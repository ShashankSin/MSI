<?php 
include('db.php'); 
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in first.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the logged-in user with prepared statements
$cart_query = $conn->prepare("SELECT * FROM Add_to_cart WHERE user_id = ?");
$cart_query->bind_param("i", $user_id);
$cart_query->execute();
$cart_result = $cart_query->get_result();

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <title>Uoni - Cart</title>
      <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="css/style.css">
      <link rel="stylesheet" href="css/responsive.css">
      <link rel="icon" href="images/fevicon.png" type="image/gif" />
      <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
      <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="css/owl.carousel.min.css">
      <link rel="stylesheet" href="css/owl.theme.default.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
      <style>
         body {
            position: relative;
            height: 100vh;
         }
         .footer_section {
            position: absolute;
            bottom: 0;
            left: 0;
         }
      </style>
   </head>
   <body>
      <!-- Header section start -->
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
                        <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="watchs.php">Watchs</a></li>
                        <li class="nav-item"><a class="nav-link" href="testimonial.php">Testimonial</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><img src="images/search-icon.png"></a></li>
                     </ul>
                  </div>
               </nav>
            </div>
            <div class="container-fluid">
               <div class="logo"><a href="index.php"><img src="images/logo.png"></a></div>
               <div class="menu_main">
                  <ul>
                     <li><a href="index.php">Home</a></li>
                     <li><a href="about.php">About</a></li>
                     <li><a href="watchs.php">Watchs</a></li>
                     <li><a href="testimonial.php">Testimonial</a></li>
                     <li><a href="contact.php">Contact us</a></li>
                     <li><a href="cart.php" class="active">Cart</a></li>
                     <li><a href="logout.php">Logout</a></li>
                     <li><a href="#"><img src="images/search-icon.png"></a></li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Cart section -->
      <div class="container mt-5">
         <h2>Your Cart</h2>
         <?php if ($cart_result->num_rows > 0): ?>
            <table class="table table-bordered">
               <thead>
                  <tr>
                     <th>Image</th>
                     <th>Product</th>
                     <th>Quantity</th>
                     <th>Price</th>
                     <th>Total</th>
                  </tr>
               </thead>
               <tbody>
                  <?php while($cart_item = $cart_result->fetch_assoc()): ?>
                     <tr>
                        <td><img src="Admin/productPics/<?php echo htmlspecialchars($cart_item['image']); ?>" alt="Product Image" width="50"></td>
                        <td><?php echo htmlspecialchars($cart_item['product_id']); ?></td>
                        <td><?php echo htmlspecialchars($cart_item['quantity']); ?></td>
                        <td>$<?php echo htmlspecialchars($cart_item['price']); ?></td>
                        <td>$<?php echo htmlspecialchars($cart_item['price'] * $cart_item['quantity']); ?></td>
                     </tr>
                     <?php $total_price += $cart_item['price'] * $cart_item['quantity']; ?>
                  <?php endwhile; ?>
               </tbody>
            </table>
            <h3>Total: $<?php echo $total_price; ?></h3>

            <!-- Redirect to payment.php instead of deleting cart items -->
            <form method="POST" action="payment.php">
               <button type="submit" class="btn btn-success">Proceed to Payment</button>
            </form>

         <?php else: ?>
            <p>Your cart is empty.</p>
         <?php endif; ?>
      </div>

      <!-- Footer section -->
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

      <!-- Javascript files-->
      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/jquery-3.0.0.min.js"></script>
      <script src="js/plugin.js"></script>
      <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="js/custom.js"></script>
      <script src="js/owl.carousel.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
   </body>
</html>
