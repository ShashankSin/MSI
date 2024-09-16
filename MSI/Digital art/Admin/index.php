<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard | Digital Art</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>

  <body>
    <!-- =============== Navigation ================ -->
    <div>
      <div class="navigation">
          <ul>
              <li><a href="#"><span class="title">Digital Art</span></a></li>
              <li><a href="index.php"><span class="icon"><ion-icon name="home-outline"></ion-icon></span><span class="title">Dashboard</span></a></li>
              <li><a href="Category.php"><span class="icon"><ion-icon name="people-outline"></ion-icon></span><span class="title">Category</span></a></li>
              <li><a href="Vendor.php"><span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span><span class="title">Vendor</span></a></li>
              <li><a href="Product.php"><span class="icon"><ion-icon name="help-outline"></ion-icon></span><span class="title">Product</span></a></li>
              <li><a href="view_product.php"><span class="icon"><ion-icon name="help-outline"></ion-icon></span><span class="title">View Product</span></a></li>
              <li><a href="#"><span class="icon"><ion-icon name="settings-outline"></ion-icon></span><span class="title">Sales</span></a></li>
              <li><a href="#"><span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span><span class="title">Report</span></a></li>
              <li><a href="#"><span class="icon"><ion-icon name="log-out-outline"></ion-icon></span><span class="title">User</span></a></li>
          </ul>
      </div>

      <!-- ========================= Main ==================== -->
      <div class="main">
        <div class="topbar">
          <div class="toggle">
            <ion-icon name="menu-outline"></ion-icon>
          </div>

          <div class="search">
            <label>
              <input type="text" placeholder="Search here" />
              <ion-icon name="search-outline"></ion-icon>
            </label>
          </div>

          <div class="user">
            <img src="assets/imgs/customer01.jpg" alt="" />
          </div>
        </div>

        <!-- ======================= Cards ================== -->
        <div class="cardBox">
          <div class="card">
            <div>
              <?php
              // Database connection
              include 'db.php';

              // Query to get the total number of products
              $query = "SELECT COUNT(*) AS total_products FROM Product";
              $result = $conn->query($query);

              if ($result) {
                  $row = $result->fetch_assoc();
                  $total_products = $row['total_products'];
              } else {
                  $total_products = 'N/A'; // Handle error
              }

              $conn->close();
              ?>
              <div class="numbers"><?php echo number_format($total_products); ?></div>
              <div class="cardName">Total Products</div>
            </div>
          </div>

          <!-- <div class="card">
            <div>
              <div class="numbers">80</div>
              <div class="cardName">Sales</div>
            </div>

            <div class="iconBx">
              <ion-icon name="cart-outline"></ion-icon>
            </div>
          </div>

          <div class="card">
            <div>
              <div class="numbers">284</div>
              <div class="cardName">Comments</div>
            </div>

            <div class="iconBx">
              <ion-icon name="chatbubbles-outline"></ion-icon>
            </div>
          </div>

          <div class="card">
            <div>
              <div class="numbers">$7,842</div>
              <div class="cardName">Earning</div>
            </div>

            <div class="iconBx">
              <ion-icon name="cash-outline"></ion-icon>
            </div>
          </div> -->
        </div>

        <!-- ================ Order Details List ================= -->
        <!-- <div class="details">
          <div class="recentOrders">
            <div class="cardHeader">
              <h2>Recent Orders</h2>
              <a href="#" class="btn">View All</a>
            </div>

            <table>
              <thead>
                <tr>
                  <td>Name</td>
                  <td>Price</td>
                  <td>Payment</td>
                  <td>Status</td>
                </tr>
              </thead>

              <tbody>
                <tr>
                  <td>Star Refrigerator</td>
                  <td>$1200</td>
                  <td>Paid</td>
                  <td><span class="status delivered">Delivered</span></td>
                </tr>

                <tr>
                  <td>Dell Laptop</td>
                  <td>$110</td>
                  <td>Due</td>
                  <td><span class="status pending">Pending</span></td>
                </tr>

                <tr>
                  <td>Apple Watch</td>
                  <td>$1200</td>
                  <td>Paid</td>
                  <td><span class="status return">Return</span></td>
                </tr>

                <tr>
                  <td>Addidas Shoes</td>
                  <td>$620</td>
                  <td>Due</td>
                  <td><span class="status inProgress">In Progress</span></td>
                </tr>

                <tr>
                  <td>Star Refrigerator</td>
                  <td>$1200</td>
                  <td>Paid</td>
                  <td><span class="status delivered">Delivered</span></td>
                </tr>

                <tr>
                  <td>Dell Laptop</td>
                  <td>$110</td>
                  <td>Due</td>
                  <td><span class="status pending">Pending</span></td>
                </tr>

                <tr>
                  <td>Apple Watch</td>
                  <td>$1200</td>
                  <td>Paid</td>
                  <td><span class="status return">Return</span></td>
                </tr>

                <tr>
                  <td>Addidas Shoes</td>
                  <td>$620</td>
                  <td>Due</td>
                  <td><span class="status inProgress">In Progress</span></td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="recentCustomers">
            <div class="cardHeader">
              <h2>Recent Customers</h2>
            </div>

            <table>
              <tr>
                <td width="60px">
                  <div class="imgBx">
                    <img src="assets/imgs/customer02.jpg" alt="" />
                  </div>
                </td>
                <td>
                  <h4>
                    David <br />
                    <span>Italy</span>
                  </h4>
                </td>
              </tr>

              <tr>
                <td width="60px">
                  <div class="imgBx">
                    <img src="assets/imgs/customer01.jpg" alt="" />
                  </div>
                </td>
                <td>
                  <h4>
                    Amit <br />
                    <span>India</span>
                  </h4>
                </td>
              </tr>

              <tr>
                <td width="60px">
                  <div class="imgBx">
                    <img src="assets/imgs/customer02.jpg" alt="" />
                  </div>
                </td>
                <td>
                  <h4>
                    David <br />
                    <span>Italy</span>
                  </h4>
                </td>
              </tr>

              <tr>
                <td width="60px">
                  <div class="imgBx">
                    <img src="assets/imgs/customer01.jpg" alt="" />
                  </div>
                </td>
                <td>
                  <h4>
                    Amit <br />
                    <span>India</span>
                  </h4>
                </td>
              </tr>

              <tr>
                <td width="60px">
                  <div class="imgBx">
                    <img src="assets/imgs/customer02.jpg" alt="" />
                  </div>
                </td>
                <td>
                  <h4>
                    David <br />
                    <span>Italy</span>
                  </h4>
                </td>
              </tr>

              <tr>
                <td width="60px">
                  <div class="imgBx">
                    <img src="assets/imgs/customer01.jpg" alt="" />
                  </div>
                </td>
                <td>
                  <h4>
                    Amit <br />
                    <span>India</span>
                  </h4>
                </td>
              </tr>

              <tr>
                <td width="60px">
                  <div class="imgBx">
                    <img src="assets/imgs/customer01.jpg" alt="" />
                  </div>
                </td>
                <td>
                  <h4>
                    David <br />
                    <span>Italy</span>
                  </h4>
                </td>
              </tr>

              <tr>
                <td width="60px">
                  <div class="imgBx">
                    <img src="assets/imgs/customer02.jpg" alt="" />
                  </div>
                </td>
                <td>
                  <h4>
                    Amit <br />
                    <span>India</span>
                  </h4>
                </td>
              </tr>
            </table>
          </div>
        </div> -->
      </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>
    
    <script src="script/script.js"></script>

    <!-- ====== ionicons ======= -->
    <script
      type="module"
      src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"
    ></script>
    <script
      nomodule
      src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  </body>
</html>
