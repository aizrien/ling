<?php
session_start();

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "lingscars";
$adminDbname = "lingscars_admin"; // Admin database name

// Create connection to lingscars database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create connection to lingscars_admin database
$adminConn = new mysqli($servername, $username, $password, $adminDbname);

// Check admin connection
if ($adminConn->connect_error) {
    die("Admin connection failed: " . $adminConn->connect_error);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and escape the email and password
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Query to check if the user exists in the lingscars_admin database
    $adminSql = "SELECT * FROM administrator WHERE emailAdmin = ?";
    $stmt = $adminConn->prepare($adminSql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $adminResult = $stmt->get_result();

    if ($adminResult->num_rows > 0) {
        $adminRow = $adminResult->fetch_assoc();
        // Get the stored password from the passAdmin field
        $adminStoredPassword = $adminRow['passAdmin'];

        // Verify the password
        if ($password === $adminStoredPassword) {
            // Admin user
            $_SESSION['emailmember'] = $email; // Store the user's email or unique identifier
            echo "<script>alert('You are logged in as admin.'); window.location.href='admin.html';</script>";
            exit();  // Stop further script execution after redirect
        } else {
            // Incorrect password for admin - Redirect to login with error
            $_SESSION['error'] = 'Incorrect password';
            header("Location: login1.php");
            exit();  // Stop further script execution after redirect
        }
    } else {
        // Query to check if the user exists in the lingscars database
        $sql = "SELECT * FROM Member WHERE emailmember = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Get the stored password hash
            $storedHash = $row['passmember'];

            // Debugging output
            error_log("Stored Hash: " . $storedHash);
            error_log("Entered Password: " . $password);

            // Verify the password
            if (password_verify($password, $storedHash)) {
                // Registered member
                $_SESSION['emailmember'] = $email; // Store the user's email or unique identifier
                echo "<script>alert('You are logged in as registered member.'); window.location.href='dashboardmember.html';</script>";
                exit();  // Stop further script execution after redirect
            } else {
                // Incorrect password - Redirect to login with error
                $_SESSION['error'] = 'Incorrect password';
                header("Location: login1.php");
                exit();  // Stop further script execution after redirect
            }
        } else {
            // Email not found - Redirect to login with error
            $_SESSION['error'] = 'Email does not exist';
            header("Location: login1.php");
            exit();  // Stop further script execution after redirect
        }
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the connections
$conn->close();
$adminConn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Anime.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="login.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        /* Add custom styles here */
        body {
            font-family: Arial, sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .login-container h1 {
            font-family: 'Lobster', cursive;
            text-align: center;
        }
        button {
            background-color: #ff5733; /* Change to desired color */
            color: white;
            padding: 12px 20px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s; /* Smooth transition for hover effect */
        }
        button:hover {
            background-color: #ff4500; /* Change to desired hover color */
        }
    </style>
    <script>
        // Function to animate form elements using anime.js
        function animateForm() {
            anime({
                targets: '.login-container',
                translateY: [-50, 0],
                opacity: [0, 1],
                duration: 1000,
                easing: 'easeOutBounce'
            });
        }
        document.addEventListener("DOMContentLoaded", function() {
            animateForm();
            <?php
            if (isset($_SESSION['error'])) {
                echo "alert('" . $_SESSION['error'] . "');";
                unset($_SESSION['error']);
            }
            ?>
        });
    </script>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center" style="font-family: 'Lobster', cursive;"></h1>
    <div class="login-container">
        <h1>Login</h1>
        <form id="loginForm" action="login1.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <!-- Link to Register Page -->
        <div class="mt-3 text-center">
        <p>I do not have an existing account <a href="register1.php">Register here</a>.</p>
    </div>
	
    </form>
</div>
</body>
</html>
