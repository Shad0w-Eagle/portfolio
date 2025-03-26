<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP user
$password = ""; // Default is empty
$dbname = "portfolio_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values safely
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? $conn->real_escape_string($_POST['subject']) : 'No Subject';
    $message = isset($_POST['message']) ? $conn->real_escape_string($_POST['message']) : '';

    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        die("Error: Please fill in all required fields.");
    }

    // Insert data into database
    $sql = "INSERT INTO contact_form (name, email, subject, message) 
            VALUES ('$name', '$email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        $status = "success";
        $message = "Your message has been sent successfully!";
    } else {
        $status = "error";
        $message = "Something went wrong. Please try again.";
    }
} else {
    header("Location: contact.html"); // Redirect if accessed directly
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            text-align: center;
        }
        .message-box {
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            max-width: 500px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = "view-messages.php";
        }, 5000);
    </script>
</head>
<body>

    <div class="message-box">
        <?php if ($status == "success"): ?>
            <h2 class="success">Success!</h2>
            <p><?php echo $message; ?></p>
        <?php else: ?>
            <h2 class="error">Error!</h2>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <p>You will be redirected in <strong>5 seconds</strong>...</p>
        <a href="view-messages.php" class="btn btn-primary mt-3">Go to Messages Now</a>
    </div>

</body>
</html>
