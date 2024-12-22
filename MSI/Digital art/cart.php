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
$cart_query = $conn->prepare("SELECT * FROM add_to_cart WHERE user_id = ?");
$cart_query->bind_param("i", $user_id);
$cart_query->execute();
$cart_result = $cart_query->get_result();

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css"
        media="screen">
    <style>
    body {
        background-color: #f5f5f5;
    }
    </style>
</head>

<body>
    <div>
        <!-- header section start -->
        <div class="header_section">
            <div class="header_main">
                <div class="mobile_menu">
                    <nav class="navbar navbar-expand-lg navbar-light bg-light">
                        <div class="logo_mobile"><a href="index.php"><img src="images/logo.png"></a></div>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                            <li><a href="logout.php">Logout</a></li>
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
                                <h1 class="banner_taital">Digital Art</h1>
                                <p class="banner_text">There are many variations of passages of Lorem Ipsum available,
                                    but
                                    the majority have suffered</p>
                                <div class="read_bt"><a href="#">Buy Now</a></div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="container">
                                <h1 class="banner_taital">Digital Art</h1>
                                <p class="banner_text">There are many variations of passages of Lorem Ipsum available,
                                    but
                                    the majority have suffered</p>
                                <div class="read_bt"><a href="#">Buy Now</a></div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="container">
                                <h1 class="banner_taital">Digital Art</h1>
                                <p class="banner_text">There are many variations of passages of Lorem Ipsum available,
                                    but
                                    the majority have suffered</p>
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
        <div class="container cart_container">
            <h2 class="mb-4">Your Cart</h2>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Product ID</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                // Fetch items in cart
                if ($cart_result->num_rows > 0) {
                    while ($cart_item = $cart_result->fetch_assoc()) {
                        $item_total = $cart_item['price'] * $cart_item['quantity'];
                        $total_price += $item_total; // Calculate total price
                        echo "<tr>
                            <td>" . htmlspecialchars($cart_item['product_id']) . "</td>
                            <td><img src='Admin/productPics/" . htmlspecialchars($cart_item['image']) . "' alt='Product Image' style='width: 100px; height: auto;'></td>
                            <td>
                                <form method='POST' action='add_to_cart.php' style='display:inline;'>
                                    <input type='hidden' name='cart_id' value='" . htmlspecialchars($cart_item['cart_id']) . "'>
                                    <input type='number' name='quantity' value='" . htmlspecialchars($cart_item['quantity']) . "' min='1' max='" . htmlspecialchars($cart_item['stock']) . "' required>
                                    <button type='submit' name='update_quantity' class='btn btn-warning'>Update</button>
                                </form>
                            </td>
                            <td>$" . number_format($item_total, 2) . "</td>
                            <td>
                                <form method='POST' action='add_to_cart.php' style='display:inline;'>
                                    <input type='hidden' name='cart_id' value='" . htmlspecialchars($cart_item['cart_id']) . "'>
                                    <button type='submit' name='delete_from_cart' class='btn btn-danger'>Remove</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No items in the cart.</td></tr>";
                }
                ?>
                </tbody>
            </table>

            <!-- Display total amount -->
            <div class="mb-3">
                <strong>Total Amount: $<?php echo number_format($total_price, 2); ?></strong>
            </div>

            <form action="payment.php" method="GET">
                <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($total_price); ?>">
                <button type="submit" class="btn btn-success">Proceed to Payment</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>