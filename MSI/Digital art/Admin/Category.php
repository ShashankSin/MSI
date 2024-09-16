<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Digital Art</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
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

    <!-- Main Content -->
    <div class="main">
        <div class="topbar">
            <div class="toggle">
                <ion-icon name="menu-outline"></ion-icon>
            </div>
            <div class="search">
                <label>
                    <input type="text" placeholder="Search here">
                    <ion-icon name="search-outline"></ion-icon>
                </label>
            </div>
            <div class="user">
                <img src="assets/imgs/customer01.jpg" alt="">
            </div>
        </div>

        <!-- Category Section -->
        <div class="Category container mt-5">
            <div>
                <div class="category-title">
                    <h3>Add Category</h3>
                </div>
                <form id="addCategory" action="addcategory.php" method="POST" enctype="multipart/form-data">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Category</span>
                        <input type="text" name="category_name" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" required>
                    </div>
                    <div class="input-group mb-3">
                        <input type="file" name="category_image" accept="image/*" class="form-control" id="inputGroupFile02" required>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
        <div class="category_display Category container mt-5">
        <?php 
            include 'db.php'; // Include the database connection

            // Fetch data from the database
            $sql = "SELECT * FROM category";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<div>";
                echo "<h2>Categories</h2>";
                echo "<table class='table'>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Category Name</th>
                                <th>Category Image</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>";
                echo "<tbody>";

                while ($row = $result->fetch_assoc()) {
                    $image_path = 'uploads/' . htmlspecialchars($row["category_image"]);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["c_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["category_name"]) . "</td>";
                    echo "<td><img src='" . $image_path . "' alt='" . htmlspecialchars($row["category_name"]) . "' style='width: 100px; height: auto;'></td>";
                    echo "<td><a href='Categoryedit.php?id=" . htmlspecialchars($row['c_id']) . "' class='btn btn-primary'>Edit</a></td>";
                    echo "<td><a href='Categorydelete.php?id=" . htmlspecialchars($row['c_id']) . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this item?\");'>Delete</a></td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            } else {
                echo "0 results";
            }

            // Close the database connection
            $conn->close();
        ?>

        
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js" type="module"></script>
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js" nomodule></script>
</body>
</html>
