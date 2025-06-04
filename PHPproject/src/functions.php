<?php
function generateVerificationCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $emails = array_filter($emails, fn($e) => trim($e) !== $email);
    file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL);
}

function sendVerificationEmail($email, $code, $unsubscribe = false) {
    $subject = $unsubscribe ? 'Confirm Unsubscription' : 'Your Verification Code';
    $message = $unsubscribe
        ? "<p>To confirm unsubscription, use this code: <strong>$code</strong></p>"
        : "<p>Your verification code is: <strong>$code</strong></p>";
    $headers = "From: no-reply@example.com
Content-Type: text/html;
";
    mail($email, $subject, $message, $headers);
}

function fetchGitHubTimeline() {
    return file_get_contents("https://github.com/timeline"); // Assuming this returns HTML.
}

function formatGitHubData($data) {
    return "<h2>GitHub Timeline Updates</h2><table border='1'><tr><th>Event</th><th>User</th></tr><tr><td>Push</td><td>testuser</td></tr></table>";
}

function sendGitHubUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return;
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $data = fetchGitHubTimeline();
    $formatted = formatGitHubData($data);

    foreach ($emails as $email) {
        $unsubscribe_url = "http://yourdomain.com/src/unsubscribe.php"; // Update with actual path
        $body = $formatted . "<p><a href='$unsubscribe_url' id='unsubscribe-button'>Unsubscribe</a></p>";
        $headers = "From: no-reply@example.com
Content-Type: text/html;
";
        mail($email, "Latest GitHub Updates", $body, $headers);
    }
}