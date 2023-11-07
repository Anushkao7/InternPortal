<?php
session_start();
include 'includes/header.php';
include '../core/init.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

if (isset($_POST['reset_password'])) {
    $email = $_POST['email'];

    $sql1 = "SELECT * FROM employer WHERE email = ?";
    $stmt1 = $db->prepare($sql1);
    $stmt1->bind_param('s', $email);
    $stmt1->execute();
    $result = $stmt1->get_result();

    if ($result->num_rows > 0) {
        echo "User exists in the database.";
    } else {
        echo '<script type="text/javascript">';
        echo 'alert("User not registered!");';
        echo 'window.location.href = "login.php";'; 
        echo '</script>';
        echo "User does not exist in the database.";
    }

    $mail = new PHPMailer(true);
    // Generate a unique reset token
    $resetToken = bin2hex(random_bytes(32)); // Generate a 64-character hexadecimal token

    // Set the expiration time for the token (e.g., 1 hour from now)
    $tokenExpiration = date('Y-m-d H:i:s', strtotime('+100 hour'));

    // Insert the token and associated email into the database
    $sql = "INSERT INTO password_reset_tokens (user_email, reset_token, token_expiration) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('sss', $email, $resetToken, $tokenExpiration);

    if ($stmt->execute()) {
        // Send an email to the user with a link that includes the reset token
        $resetLink = "http://localhost/internship/password_reset.php?token=" . $resetToken;
        // Send the reset email with the $resetLink
        // echo $resetLink;

        try {
            // Set mailer to use SMTP
            $mail->isSMTP();
        
            // Specify the SMTP server
            $mail->Host = 'smtp.gmail.com';
        
            // Enable SMTP debugging (0 = off, 1 = client messages, 2 = client and server messages)
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
        
            // Set the SMTP port for Gmail
            $mail->Port = 465;
            $mail->SMTPSecure = "ssl";
            // Enable SMTP authentication
            $mail->SMTPAuth = true;
        
            // Your Gmail username (email address)
            $mail->Username = 'internportal40@gmail.com';
        
            // Your Gmail password or an app password (if you have 2-Step Verification enabled)
            $mail->Password = 'zgtpnqobvpujskgn';
        
            // Set the sender's email address and name
            $mail->setFrom('internportal40@gmail.com', 'Intern Portal');
        
            // Add the recipient's email address and name
            $mail->addAddress($email, 'User');
        
            // Email subject and body
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset';
            $mail->Body = 'Hello, click on this link to reset the password.'    . "\n" . $resetLink;
        
            // Send the email
            $mail->send();
            echo 'Email sent successfully.';
            $mail->smtpClose();
        } catch (Exception $e) {
            echo 'Email sending failed. Error: ' . $mail->ErrorInfo;
        }
        // Provide feedback to the user
        echo "<p>Password reset instructions have been sent to your email.</p>";
    } else {
        echo "<p>Error storing the reset token.</p>";
    }

    $stmt->close();
}
?>
<!-- You can include a message here to inform the user about the email sent. -->

<?php
include 'includes/footer.php';
?>

