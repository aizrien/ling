<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="keyinfo.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .receipt-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .receipt-heading {
            text-align: center;
        }
        .receipt-details {
            margin-top: 20px;
        }
        .detail-item {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            background-color: maroon;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            margin-right: 10px;
        }
        .btn-print {
            background-color: maroon;
        }
        .btn:hover {
            background-color: #800000; /* Darker shade of maroon */
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <h1 class="receipt-heading">Order Receipt</h1>
        
        <div class="receipt-details">
            <div class='detail-item'><span class='detail-label'>Name:</span> <?php echo htmlspecialchars($_GET['firstName'] . ' ' . $_GET['lastName']); ?></div>
            <div class='detail-item'><span class='detail-label'>Phone:</span> <?php echo htmlspecialchars($_GET['phone']); ?></div>
            <div class='detail-item'><span class='detail-label'>Address:</span> <?php echo htmlspecialchars($_GET['address']); ?></div>
            <div class='detail-item'><span class='detail-label'>Vehicle Brand:</span> <?php echo htmlspecialchars($_GET['brand']); ?></div>
            <div class='detail-item'><span class='detail-label'>Vehicle Model:</span> <?php echo htmlspecialchars($_GET['model']); ?></div>
            <div class='detail-item'><span class='detail-label'>Vehicle Spec:</span> <?php echo htmlspecialchars($_GET['spec']); ?></div>
            <div class='detail-item'><span class='detail-label'>Vehicle Price:</span> <?php echo htmlspecialchars($_GET['price']); ?></div>
            <div class='detail-item'><span class='detail-label'>Preferred Arrival Date:</span> <?php echo htmlspecialchars($_GET['arrivaldate']); ?></div>
        </div>
        
        <div class="btn-container">
            <a href="dashboardmember.html" class="btn">&laquo; Back to Menu</a>
            <button class="btn btn-print" onclick="window.print()">Print</button>
        </div>
    </div>
</body>
</html>
