<?php
include('connection.php');

$notification = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["fullname"]);
    $email = htmlspecialchars($_POST["email"]);
    $subject = htmlspecialchars($_POST["subject"]);
    $message = htmlspecialchars($_POST["message"]);
    $to = "franklinkencarranza@gmail.com";

    $message_body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
    $headers = "From: franklinkencarranza@gmail.com\nReply-To: $email";

    if (mail($to, $subject, $message_body, $headers)) {
        $notification = "Email Sent Successfully!";
    } else {
        $notification = "Email Sending Failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greenleaf</title>
    <link rel="stylesheet" href="../css/contact.css">
    <script src="../js/fade.js" defer></script>
</head>
<body>

    <?php if (!empty($notification)): ?>
        <div class="message <?php echo (strpos($notification, 'Successfully') !== false) ? 'success' : 'error'; ?>">
            <?php echo $notification; ?>
        </div>
    <?php endif; ?>

    <section>
        <nav>
            <a href="home.php"><img src="../images/logo.png" class="logo"></a>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="rooms.php">Rooms</a></li>
                <li><a href="team.php">Team</a></li>
                <li class="custom-link"><a href="contact.php">Contact</a></li>
            </ul>
            <a href="../admin/login.php"><img src="../images/menu.svg" class="menu-img"></a>
        </nav>

        <form method="POST" class="contact-left">
            <div>
                <h2>Contact Us</h2>
                <hr>
                <label for="Name">Name</label>
                <input class="text" id="Name" type="text" placeholder="Name" name="fullname" required>
                <br>
                <label for="email">Email</label>
                <input class="text" id="email" type="email" placeholder="Email" name="email" required>
                <br>
                <label for="subject">Subject</label>
                <input class="text" id="subject" type="text" placeholder="Subject" name="subject" required>
                <br>
                <label for="concern">Concern</label>
                <input class="concern" id="concern" type="text" placeholder="Concern" name="message" required>
                <br>
                <button type="submit">Submit</button>
            </div>
        </form>
    </section>

</body>
</html>
