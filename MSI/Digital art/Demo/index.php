<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD App</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h2>Add Product</h2>
<form id="addProduct">
    <input type="text" name="product_name" placeholder="Product Name" required>
    <input type="number" name="product_price" placeholder="Product Price" required>
    <button type="submit">Add Product</button>
</form>

<h2>Update Product</h2>
<form id="updateProduct" style="display:none;">
    <input type="hidden" id="product_id" name="p_id">
    <input type="text" id="product_name" name="product_name" placeholder="Product Name" required>
    <input type="number" id="product_price" name="product_price" placeholder="Product Price" required>
    <button type="submit">Update Product</button>
</form>

<h2>Products List</h2>
<table id="productTable" border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <!-- Products will be loaded here -->
    </tbody>
</table>

<script src="js/script.js"></script>
</body>
</html>
