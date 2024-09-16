<?php 

include('db.php');

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
   </head>
   <body>
      <!-- header section start -->
      <div class="header_section">
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
                     <li><a href="Login.php">Login</a></li>
                     <li><a href="#"><img src="images/search-icon.png"></a></li>
                  </ul>
               </div>
            </div>
         </div>
         <!-- banner section start -->
         <div class="banner_section layout_padding">
            <div id="main_slider" class="carousel slide" data-ride="carousel">
               <div class="carousel-inner">
                  <div class="carousel-item active">
                     <div class="container">
                        <h1 class="banner_taital">Watchs</h1>
                        <p class="banner_text">There are many variations of passages of Lorem Ipsum available, but the majority have suffered</p>
                        <div class="read_bt"><a href="#">Buy Now</a></div>
                     </div>
                  </div>
                  <div class="carousel-item">
                     <div class="container">
                        <h1 class="banner_taital">Watchs</h1>
                        <p class="banner_text">There are many variations of passages of Lorem Ipsum available, but the majority have suffered</p>
                        <div class="read_bt"><a href="#">Buy Now</a></div>
                     </div>
                  </div>
                  <div class="carousel-item">
                     <div class="container">
                        <h1 class="banner_taital">Watchs</h1>
                        <p class="banner_text">There are many variations of passages of Lorem Ipsum available, but the majority have suffered</p>
                        <div class="read_bt"><a href="#">Buy Now</a></div>
                     </div>
                  </div>
               </div>
               <a class="carousel-control-prev" href="#main_slider" role="button" data-slide="prev">
               <i class="fa fa-plus" style="font-size:24px; color: #fff;"></i>
               </a>
               <a class="carousel-control-next" href="#main_slider" role="button" data-slide="next">
               <i class="fa fa-minus" style="font-size:24px;  color: #fff;"></i>
               </a>
            </div>
         </div>
         <!-- banner section end -->
      </div>
      <!-- header section end -->
      <!-- background bg start -->
      <div class="background_bg">
         <!-- watchs section start -->
         <div class="watchs_section layout_padding">
            <div class="container">
               <h1 class="watchs_taital">Our Products</h1>
               <div class="watchs_section_2">
                  <div class="row gap-3">
                     <?php
                     // Fetch products
                     $product_query = "SELECT * FROM Product";
                     $product_result = $conn->query($product_query);

                     if (!$product_result) {
                        die("Query failed: " . $conn->error);
                     }
                     if ($product_result->num_rows > 0) {
                         // Output data of each row
                         while($product = $product_result->fetch_assoc()) {
                             echo '
                             <div class= "p-5 col-6">
                                <div class="image_1"><img src="Admin/productPics/'. htmlspecialchars($product["product_image"]) .'" alt="'. htmlspecialchars($product["product_name"]) .'"></div>
                                <h4 class="uni_text">'. htmlspecialchars($product["product_name"]) .'</h4>
                                <p class="watchs_text">'. htmlspecialchars($product["product_details"]) .'</p>
                                <h4 class="rate_text"><span style="color: #b60213;">$</span>'. htmlspecialchars($product["product_price"]) .'</h4>
                                 <div>
                                    <input type="hidden" name="product_id" value="' . htmlspecialchars($product["p_id"]) . '">
                                    <input type="number" name="quantity" value="1" min="1" max="' . htmlspecialchars($product["product_stock"]) . '">
                                    <a href="Login.php">
                                       <button type="submit" name="add_to_cart">Add to Cart</button>
                                    </a>
                                 </div>
                             </div>
                             ';
                         }
                     } else {
                         echo "<p>No products found.</p>";
                     }
                     ?>
                  </div>
               </div>
            </div>
         </div>
      <!-- background bg end -->
      <!-- footer section start -->
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
      <!-- copyright section start -->
      <div class="copyright_section">
         <div class="container">
            <p class="copyright_text">2020 All Rights Reserved. Design by <a href="https://html.design">Free html  Templates</a></p>
         </div>
      </div>
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