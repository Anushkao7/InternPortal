<?php
session_start();
include 'includes/header.php';
include '../core/init.php';

$divVisible = true;

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify the token from the URL against the one stored in the database
    $sql = "SELECT user_email, token_expiration FROM password_reset_tokens WHERE reset_token = ? AND token_expiration > NOW()";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userEmail, $tokenExpiration);
        $stmt->fetch();
        $stmt->close();

        // The token is valid, allow the user to reset the password
        if (isset($_POST['reset_password'])) {
            $newPassword = $_POST['new_password'];
            // Hash and securely store the new password in the database
            // Update the user's password in the database using their email
            // $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $updateSql = "UPDATE customers SET password = ? WHERE email = ?";
            $updateStmt = $db->prepare($updateSql);
            $updateStmt->bind_param('ss', $newPassword, $userEmail);

            if ($updateStmt->execute()) {
                // Password reset successful
                echo "<p>Your password has been reset successfully.</p>";
                $divVisible = false;
                // Optionally, you can log the user in here or provide a login link.
            } else {
                echo "<p>Error resetting the password.</p>";
            }
            $updateStmt->close();
        }

        // Display a password reset form
        ?>

        <?php if ($divVisible): ?>

        <form action="" method="post">
            <div class="md-form form-sm" id="pass-box">
                <input type="password" id="new_password" class="form-control form-control-sm" name="new_password" required>
                <label for="new_password">New Password</label>
            </div>
            <div class="float-right">
                <button type="submit" name="reset_password" class="btn btn-black" style="border-radius: 10em; background: #1c2a48">Reset Password</button>
            </div>
        </form>

        <?php endif; ?>

        <?php
    } else {
        echo "<p>Invalid or expired token.</p>";
    }
} else {
    echo "<p>Token missing in the URL.</p>";
}
?>

<!-- You can include a password reset form here. -->

<?php
include 'includes/footer.php';
?>

