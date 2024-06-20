<?php
// Start the session
session_start();

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "lingscars";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch data from the form, and sanitize input
    $email = isset($_POST["email"]) ? $conn->real_escape_string($_POST["email"]) : "";
    $password = isset($_POST["password"]) ? $conn->real_escape_string($_POST["password"]) : "";
    $fullName = isset($_POST["full_name"]) ? $conn->real_escape_string($_POST["full_name"]) : "";

    // Check if password meets requirements
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 6) {
        $_SESSION['error'] = "Password should be at least 6 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $fullName)) {
        $_SESSION['error'] = "Full name can only contain alphabets and spaces.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Check if email already exists
        $check_query = "SELECT emailMember FROM Member WHERE emailMember = '$email'";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows > 0) {
            // Email already in use
            $_SESSION['error'] = "Email is already in use.";
        } else {
            // SQL query to insert data into the member table
            if (!empty($email) && !empty($password) && !empty($fullName)) {
                $sql = "INSERT INTO Member (emailMember, passMember, fullName) VALUES ('$email', '$hashed_password', '$fullName')";
                
                // Execute the query
                if ($conn->query($sql) === TRUE) {
                    $_SESSION['success'] = "Registration successful!";
                    header("Location: login1.php"); // Redirect to the login page
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                $_SESSION['error'] = "All fields are required.";
            }
        }
    }
    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
	
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="register.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration and Login Form</title>
    <style>
        /* Add custom styles here */
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
        // Client-side validation for password and full name requirements
        function validateForm() {
            var fullName = document.getElementById("full_name").value;
            var password = document.getElementById("password").value;
            var namePattern = /^[a-zA-Z\s]+$/;
            var passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^\w\s]).{6,}$/;

            if (!namePattern.test(fullName)) {
                alert("Full name can only contain alphabets and spaces.");
                return false;
            }

            if (!passwordPattern.test(password)) {
                alert("Password should be at least 6 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.");
                return false;
            }

            return true;
        }

        // Function to animate form elements using anime.js
        function animateForm() {
            anime({
                targets: '.registration-fields',
                translateY: [-50, 0],
                opacity: [0, 1],
                duration: 1000,
                easing: 'easeOutBounce'
            });
        }
    </script>
</head>
<body onload="animateForm()">

<div class="container mt-5">
    <h1 class="text-center" style="font-family: 'Lobster', cursive;">Welcome to Ling's Cars!</h1>
    <form id="registration-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="return validateForm()">
        <!-- Registration Fields -->
        <div class="registration-fields">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" placeholder="Enter Email Address" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" placeholder="Confirm Password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </div>
    </form>
  
    <!-- Link to Login Page -->
    <div class="login-link mt-3 text-center">
        <p>Already have an account? <a href="login1.php">Login here</a>.</p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php
            if (isset($_SESSION['error'])) {
                echo "alert('" . $_SESSION['error'] . "');";
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo "alert('" . $_SESSION['success'] . "');";
                unset($_SESSION['success']);
            }
            ?>
        });
    </script>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
