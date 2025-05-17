<?php
// Database connection
$servername = "localhost:3306"; 
$username = "root";             
$password = "";                 
$dbname = "reservationdb";      

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current date in the correct format for SQL (YYYY-MM-DD)
$current_date = date('Y-m-d');

// Query to get the number of current customers (unique ids for customers)
$current_customers_query = "
    SELECT COUNT(DISTINCT id) as total_customers
    FROM reservations
    WHERE check_in <= '$current_date' AND check_out >= '$current_date'
";
$customers_result = $conn->query($current_customers_query);

// Check the result
if ($customers_result && $customers_result->num_rows > 0) {
    $current_customers = $customers_result->fetch_assoc()['total_customers'];
} else {
    $current_customers = 0;
}

// Query to calculate the total revenue (total price for the current day)
$revenue_query = "SELECT SUM(price) as total_revenue FROM reservations WHERE check_in <= '$current_date' AND check_out >= '$current_date'";
$revenue_result = $conn->query($revenue_query);

// If the result is valid, process it
if ($revenue_result && $revenue_result->num_rows > 0) {
    $revenue_data = $revenue_result->fetch_assoc();
    // If SUM(price) is NULL, set total_revenue to 0
    $total_revenue = $revenue_data['total_revenue'] ? $revenue_data['total_revenue'] : 0;
} else {
    // No matching records, so set revenue to 0
    $total_revenue = 0;
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .dashboard {
            width: 90%;
            max-width: 1200px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #a0e548;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .navbar h1 {
            color: black;
            font-size: 24px;
        }

        .navbar ul {
            list-style: none;
            display: flex;
        }

        .navbar ul li {
            margin-left: 20px;
        }

        .navbar ul li a {
            color: black;
            text-decoration: none;
            padding: 10px 15px;
            transition:0.3s ease;
        }

        .navbar ul li a:hover {
            background-color: #1c598a;
            border-radius: 5px;
        }

        .main-content {
            padding: 20px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        @media (max-width: 768px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .cards {
                grid-template-columns: repeat(1, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <nav class="navbar">
            <h1>Admin Panel</h1>
            <ul>
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="reservation.php">Reservation</a></li>
                <li><a href="email.php">Email</a></li>
                <li><a href="../php/home.php">Logout</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <header class="header">
                <h2>Dashboard Overview</h2>
                <p>Welcome back, Admin!</p>
            </header>

            <section class="cards">
                <div class="card">
                    <h3>Total Rooms</h3>
                    <p>50</p>
                </div>
                <div class="card">
                    <h3>Current Customers</h3>
                    <p><?php echo $current_customers; ?></p>
                </div>
                <div class="card">
                    <h3>Total Revenue</h3>
                    <p>₱<?php echo number_format($total_revenue, 2); ?></p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
