<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="newadmin.css">
    <title>Dashboard</title>
    <style>
        /* Styles for table*/
        .overlay {
            position: fixed;
            top: 50%; /* Set top position to center */
            left: 50%; /* Set left position to center */
            transform: translate(-50%, -50%); /* Center the element */
            width: 75%;
            height: 90%;
            background-color: rgba(255, 255, 255, 2); /* Adjust the opacity as needed */
            z-index: 999; /* Ensure it's above other elements */
            border-radius: 20px; /* Set the border radius */
        }

        /* Styles for the table */
        table {
			top: 50%; /* Set top position to center */
            left: 50%; /* Set left position to center */
            width: 100%;
            border-collapse: collapse;
            margin-top: 100px; /* Adjust the spacing from the top */
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
			font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
			color: #333; /* Dark gray text color for header cells */
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <nav>
        <img src="logo1.png" alt="Logo" class="logo" width="500" height="500">
        <!-- Links for direct navigation -->
        <a href="admin.html">Dashboard</a>
        <a href="listings.html">Listing</a>
        <a href="addlistings.html">Add List</a>
        <a href="updatelistings.html">Update List</a>
        <a href="deletelistings.html">Delete Lists</a>
		<a href="display_lists.php" class="spec-button">Display Lists</a>
        <a href="dashboard.html" onclick="logout()">Log Out</a>
    </nav>

    <!-- White square overlapping the background -->
    <div class="overlay">
        <?php
        // Database connection parameters
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";
        $dbname = "lingscars_admin";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Execute SQL query
        $sql = "SELECT vehicleID, adminID, vehicleType, vehicleName, vehicleBrand, vehicleModel, vehicleSpec, vehiclePrice FROM vehicle ORDER BY vehicleID";
        $result = $conn->query($sql);

        // Check if there are rows in the result set
        if ($result->num_rows > 0) {
            // Output data of each row
            echo "<table>";
            echo "<tr><th>Vehicle ID</th><th>Administrator ID</th><th>Vehicle Type</th><th>Vehicle Name</th><th>Vehicle Brand</th><th>Vehicle Model</th><th>Vehicle Specs</th><th>Vehicle Price</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["vehicleID"] . "</td>";
                echo "<td>" . $row["adminID"] . "</td>";
                echo "<td>" . $row["vehicleType"] . "</td>";
                echo "<td>" . $row["vehicleName"] . "</td>";
                echo "<td>" . $row["vehicleBrand"] . "</td>";
                echo "<td>" . $row["vehicleModel"] . "</td>";
                echo "<td>" . $row["vehicleSpec"] . "</td>";
                echo "<td>" . $row["vehiclePrice"] . "</td>";
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
    function logout() {
        // Display a pop-up message
        alert("You have been logged out.");
    }
	function navigateToCategory(selectElement) {
            // Get the selected category
            var selectedCategory = selectElement.value;

            // Redirect to the selected category
            window.location.hash = selectedCategory;
        }
	</script>

</body>
</html>
