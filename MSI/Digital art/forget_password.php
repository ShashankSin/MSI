<?php 
include('db.php');
session_start();

// Initialize variables
$reset_error = '';
$reset_success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = trim($_POST['user_email']);

    // Sanitize inputs
    $user_email = htmlspecialchars($user_email);

    // Basic validation
    if (empty($user_email)) {
        $reset_error = 'Email is required.';
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $reset_error = 'Invalid email format.';
    } else {
        // Check if email exists in the database
        $query = "SELECT * FROM User WHERE user_email='$user_email'";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            // Generate a unique token
            $token = bin2hex(random_bytes(50));
            $token_expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token valid for 1 hour

            // Store token in the database
            $query = "UPDATE User SET reset_token='$token', token_expiry='$token_expiry' WHERE user_email='$user_email'";
            $conn->query($query);

            // Send reset link via email
            $reset_link = "http://yourdomain.com/reset_password.php?token=$token";
            $to = $user_email;
            $subject = 'Password Reset Request';
            $message = "To reset your password, please click the following link: $reset_link";
            $headers = 'From: no-reply@yourdomain.com';

            if (mail($to, $subject, $message, $headers)) {
                $reset_success = 'A password reset link has been sent to your email address.';
            } else {
                $reset_error = 'Failed to send reset email.';
            }
        } else {
            $reset_error = 'No account found with that email address.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Forgot Password</h2>
                <?php if ($reset_error): ?>
                    <div class="alert alert-danger"><?php echo $reset_error; ?></div>
                <?php endif; ?>
                <?php if ($reset_success): ?>
                    <div class="alert alert-success"><?php echo $reset_success; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="user_email">Email address</label>
                        <input type="email" class="form-control" id="user_email" name="user_email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
