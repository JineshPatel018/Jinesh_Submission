<?php
require_once 'functions.php';
session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["email"])) {
        $email = trim($_POST["email"]);
        $code = generateVerificationCode();
        $_SESSION['verification_email'] = $email;
        $_SESSION['verification_code'] = $code;
        sendVerificationEmail($email, $code);
        $message = "Verification code sent to $email";
    } elseif (isset($_POST["verification_code"])) {
        $code = trim($_POST["verification_code"]);
        if ($_SESSION['verification_code'] === $code) {
            registerEmail($_SESSION['verification_email']);
            $message = "Email verified and registered successfully.";
        } else {
            $message = "Invalid verification code.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register Email</title></head>
<body>
    <h2>Register for GitHub Timeline Updates</h2>
    <form method="post">
        <input type="email" name="email" required>
        <button id="submit-email">Submit</button>
    </form>
    <form method="post">
        <input type="text" name="verification_code" maxlength="6" required>
        <button id="submit-verification">Verify</button>
    </form>
    <p><?php echo $message; ?></p>
</body>
</html>