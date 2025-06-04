<?php
require_once 'functions.php';
session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["unsubscribe_email"])) {
        $email = trim($_POST["unsubscribe_email"]);
        $code = generateVerificationCode();
        $_SESSION['unsubscribe_email'] = $email;
        $_SESSION['unsubscribe_code'] = $code;
        sendVerificationEmail($email, $code, true);
        $message = "Unsubscribe verification code sent to $email";
    } elseif (isset($_POST["unsubscribe_verification_code"])) {
        $code = trim($_POST["unsubscribe_verification_code"]);
        if ($_SESSION['unsubscribe_code'] === $code) {
            unsubscribeEmail($_SESSION['unsubscribe_email']);
            $message = "You have been unsubscribed successfully.";
        } else {
            $message = "Invalid unsubscription code.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Unsubscribe</title></head>
<body>
    <h2>Unsubscribe from GitHub Timeline</h2>
    <form method="post">
        <input type="email" name="unsubscribe_email" required>
        <button id="submit-unsubscribe">Unsubscribe</button>
    </form>
    <form method="post">
        <input type="text" name="unsubscribe_verification_code">
        <button id="verify-unsubscribe">Verify</button>
    </form>
    <p><?php echo $message; ?></p>
</body>
</html>