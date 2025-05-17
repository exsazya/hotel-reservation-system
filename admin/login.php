<?php
// Start session to manage user authentication
session_start();

// Database connection
$servername = "localhost:3306"; // Replace with your server name
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password
$dbname = "reservationdb"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Prepare and execute query
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Check if the entered password matches the one in the database
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: admin.php"); // Redirect to dashboard
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No account found with that email.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>

          
        @font-face {
            font-family: 'black';
            font-weight:400;
            src:url(../font/poppins-black-webfont.woff2) format('woff2'),
                url(../font/poppins-black-webfont.woff) format('woff');
        }

        @font-face {
            font-family: 'medium';
            src:url(../font/poppins-medium-webfont.woff2) format('woff2'),
                url(../font/poppins-medium-webfont.woff) format('woff');
        }

        @font-face {
            font-family: 'regular';
            src:url(../font/poppins-regular-webfont.woff2) format('woff2'),
                url(../font/poppins-regular-webfont.woff) format('woff');
        }

        @font-face {
            font-family: 'light';
            src:url(../font/poppins-light-webfont.woff2) format('woff2'),
                url(../font/poppins-light-webfont.woff) format('woff');
        }

        * {
            margin: 0px;
            padding: 5px;
            box-sizing: border-box;
            font-family: 'Kanit', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 95vh;
            background: url(../images/bg.jpg) no-repeat;
            background-size: cover;
            background-position: center;
        }

        .container {
            height: 90%;
            width:400px;
            background: rgba(0,0,0,0.0);
            border-radius: 20px;
            border: 2px solid white;
            box-shadow:  5px 3px  rgba(0, 0, 0, 0.527);
            padding:35px
        }

        .container h1 {
            font-family: 'black';
            text-align: center;
            margin-bottom: 40px;
            color: black;
            font-size: 40px;
        }

        .form-group {
            margin: 20px 0;
        }

        .form-group label {
            font-family: 'medium';
            display: block;
            margin-bottom: 10px;
            color: black;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            height: 40px;
            background: transparent;
            border: none;
            outline: none;
            padding: 20px;
            font-size: 15px;
            border: 1px solid white;
            border-radius: 20px;
            border-top-right-radius: 0;
            font-family:'regular';
        }

        .form-group input::placeholder {
            color: #000000;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            font-family: 'regular';
            border: 2px solid #72fa93;
            color:black;
            text-decoration:none;
            position: relative;
            border-radius: 20px;
            border-top-right-radius: 0;
            padding: 15px;
            transition: 1s;
            width:8rem;
            text-align: center;
            font-size: 15px;
            font-weight: 600;
            background-color:#F5F5DC;
        }

        .btn:hover {
            background-color:  #72fa93;
            box-shadow: black 2px 1px;
            background-image:linear-gradient( 50deg, #72fa93, #a0e548);
            border-top-right-radius: 20px;
        }

        .error {
            font-family: 'regular';
            color: red;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="btn-group">
                <a href="../php/home.php" class="btn">Back</a>
                <button type="submit" class="btn">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
