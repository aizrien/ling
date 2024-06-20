<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="newadmin.css">
    <title>Dashboard</title>
    <style>
        /* Styles for table */
        .overlay {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 75%;
            height: 90%;
            background-color: rgba(255, 255, 255, 0.9); /* Adjust the opacity as needed */
            z-index: 999;
            border-radius: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 100px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }
		
        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
		  .button {
            background-color: maroon; /* Maroon background color */
            color: white; /* Text color */
            padding: 10px 20px; /* Padding inside the button */
            border: none; /* Remove border */
            cursor: pointer; /* Cursor style */
            border-radius: 5px; /* Rounded corners */
        }

        .button:hover {
            background-color: #800000; /* Darker maroon on hover */
        }
		 /* Adjust specificity to ensure it overrides other styles */
		.overlay .button {
        background-color: maroon;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .overlay .button:hover {
        background-color: #800000;
    }
    </style>
</head>
<body>
    <nav>
        <img src="logo1.png" alt="Logo" class="logo" width="500" height="500">
        <a href="admin.html">Dashboard</a>
        <a href="managemembers.html">Manage Member</a>
        <a href="updatemember.html">Update Member</a>
        <a href="deletemember.html">Delete Member</a>
        <a href="displaymember.php">Display Member</a>
        <a href="dashboard.html">Log Out</a>
    </nav>

    <div class="overlay">
        <?php
        // Database connection parameters
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

        // Execute SQL query
        $sql = "SELECT memberID, emailMember, passMember FROM member";
        $result = $conn->query($sql);

        // Check if there are rows in the result set
        if ($result->num_rows > 0) {
            // Output data of each row
            echo "<table>";
            echo "<tr><th>Member ID</th><th>Member Email</th><th>Member Password</th><th>Show Password</th></tr>";
            while ($row = $result->fetch_assoc()) {
                $hashedPass = $row["passMember"];
                $unhashedPass = ''; // Retrieve the unhashed password securely

                echo "<tr>";
                echo "<td>" . $row["memberID"] . "</td>";
                echo "<td>" . $row["emailMember"] . "</td>";
                echo "<td class='password' data-hash='" . $hashedPass . "'>" . $hashedPass . "</td>";
                echo "<td><button class='button' onclick='togglePassword(this)'>Show Password</button></td>";

                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "0 results";
        }

        // Close connection
        $conn->close();
        ?>
    </div>

    <footer>
        &copy; 2024 Admin Dashboard. All rights reserved.
    </footer>

    <script>
        function togglePassword(button) {
            var passwordCell = button.parentElement.previousElementSibling;
            var isHashed = passwordCell.classList.contains('password');

            if (isHashed) {
                // Retrieve the unhashed password securely (e.g., via an AJAX call to a secure endpoint)
                var unhashedPass = 'UnhashedPassword123'; // This is just a placeholder. Implement securely.

                passwordCell.textContent = unhashedPass;
                passwordCell.classList.remove('password');
                button.textContent = 'Hide Password';
            } else {
                var hashedPass = passwordCell.getAttribute('data-hash');
                passwordCell.textContent = hashedPass;
                passwordCell.classList.add('password');
                button.textContent = 'Show Password';
            }
        }
    </script>
</body>
</html>
