<?php
// Database connection (if you need to fetch data)
include('../php/connection.php');

// Handle form submission for sending email
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipient_email = $_POST['recipient_email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // Admin email (you can set this to the admin's email address)
    $admin_email = "admin@example.com"; // Change this to your admin email address

    // Set the "From" header to include the name "Green Leaf"
    $from_name = "Green Leaf";
    $headers = "From: $from_name <$admin_email>\r\n";  // Sender's name and email
    $headers .= "Reply-To: $admin_email\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";

    // Send the email using PHP's mail() function
    if (mail($recipient_email, $subject, $message, $headers)) {
        $success_message = "Email sent successfully!";
    } else {
        $error_message = "Failed to send email. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Email Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
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
            background-color:  #a0e548;
            color: black;;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .navbar h1 {
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
            transition: background 0.3s ease;
        }

        .navbar ul li a:hover {
            background-color: #1c598a;
            border-radius: 5px;
        }

        .email-form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 600px;
            margin: 30px auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1c598a;
        }

        .message {
            text-align: center;
            margin-top: 10px;
            font-size: 16px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
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
            <div class="email-form-container">
                <h1>Send Email</h1>
                
                <!-- Display success or error messages -->
                <?php if (isset($success_message)): ?>
                    <div class="message success"><?php echo $success_message; ?></div>
                <?php elseif (isset($error_message)): ?>
                    <div class="message error"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <form method="POST" action="email.php">
                    <input type="email" name="recipient_email" placeholder="Recipient's Email" required>
                    <input type="text" name="subject" placeholder="Subject" required>
                    <textarea name="message" rows="5" placeholder="Your message..." required></textarea>
                    <button type="submit">Send Email</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
