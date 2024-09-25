<?php
session_start();
include('db.php');

// Fetch product sales data
$query = "
    SELECT p.product_name, SUM(pm.amount) AS total_sales
    FROM product p
    JOIN cart_items ci ON p.p_id = ci.product_id
    JOIN orders o ON ci.cart_id = o.cart_id
    JOIN payment pm ON o.order_id = pm.order_id
    GROUP BY p.product_name
";
$result = $conn->query($query);

$dataPoints = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $dataPoints[] = [
            'label' => $row['product_name'],
            'y' => (float)$row['total_sales'],
        ];
    }
} else {
    echo "Error fetching data: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Sales Report</title>
    <script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script>
        window.onload = function () {
            var dataPoints = <?php echo json_encode($dataPoints); ?>;

            var chart = new CanvasJS.Chart("chartContainer",
            {
                title: {
                    text: "Sales Report by Product"
                },
                data: [{
                    type: "pie",
                    indexLabel: "{label}: #percent%",
                    toolTipContent: "{label}: {y} USD",
                    dataPoints: dataPoints
                }]
            });

            chart.render();
        }
    </script>
     <!-- ======= Styles ====== -->
     <link rel="stylesheet" href="assets/css/style.css" />
    <script src="path/to/chartjs/dist/chart.umd.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div>
      <div class="navigation">
        <ul>
            <li><a href="#"><span class="title">Digital Art</span></a></li>
            <li><a href="index.php"><span class="icon"><ion-icon name="home-outline"></ion-icon></span><span class="title">Dashboard</span></a></li>
            <li><a href="Category.php"><span class="icon"><ion-icon name="people-outline"></ion-icon></span><span class="title">Category</span></a></li>
            <li><a href="Product.php"><span class="icon"><ion-icon name="help-outline"></ion-icon></span><span class="title">Product</span></a></li>
            <li><a href="view_product.php"><span class="icon"><ion-icon name="help-outline"></ion-icon></span><span class="title">View Product</span></a></li>
            <li><a href="Sale.php"><span class="icon"><ion-icon name="settings-outline"></ion-icon></span><span class="title">Sales</span></a></li>
            <li><a href="Report.php"><span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span><span class="title">Report</span></a></li>
            <li><a href="User.php"><span class="icon"><ion-icon name="log-out-outline"></ion-icon></span><span class="title">User</span></a></li>
        </ul>
      </div>
    <div class="main">
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>

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
