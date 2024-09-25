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

$labels = [];
$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['product_name'];
        $data[] = $row['total_sales'];
    }
} else {
    echo "Error fetching data: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .card-body{
            height: 500px;
            width: 500px;
            overflow: hidden;
            object-fit: contain;
        }
    </style>
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
      <!-- ========================= Main ==================== -->
      <div class="main">
      <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Sales Report</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" width="500" height="500"></canvas>
            </div> 
        </div>
    </div>
      </div>
</div>
    <script>
        const labels = <?php echo json_encode($labels); ?>;
        const data = <?php echo json_encode($data); ?>;

        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'polarArea',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Sales by Product',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        angleLines: {
                            display: false
                        },
                        suggestedMin: 0,
                        suggestedMax: Math.max(...data) + 1000, // Adjust as needed
                    }
                }
            }
        });
    </script>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
