<?php 
include('db.php');
session_start();

// Initialize variables
$update_error = '';
$update_success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Sanitize inputs
    $new_password = htmlspecialchars($new_password);
    $confirm_password = htmlspecialchars($confirm_password);

    // Basic validation
    if (empty($new_password) || empty($confirm_password)) {
        $update_error = 'Both password fields are required.';
    } elseif ($new_password !== $confirm_password) {
        $update_error = 'Passwords do not match.';
    } elseif (strlen($new_password) < 6) {
        $update_error = 'Password must be at least 6 characters long.';
    } else {
        // Check if token is valid
        $query = "SELECT * FROM user WHERE reset_token='$token' AND token_expiry > NOW()";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password and clear token
            $query = "UPDATE user SET user_password='$hashed_password', reset_token=NULL, token_expiry=NULL WHERE reset_token='$token'";
            if ($conn->query($query)) {
                $update_success = 'Your password has been updated. You can now <a href="login.php">login</a>.';
            } else {
                $update_error = 'Failed to update password.';
            }
        } else {
            $update_error = 'Invalid or expired token.';
        }
    }
} else {
    $token = $_GET['token'] ?? '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Reset Password</h2>
                <?php if ($update_error): ?>
                    <div class="alert alert-danger"><?php echo $update_error; ?></div>
                <?php endif; ?>
                <?php if ($update_success): ?>
                    <div class="alert alert-success"><?php echo $update_success; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
