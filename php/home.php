<?php
include('connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greenleaf</title>
    <link rel="stylesheet" href="../css/home.css">
    <script src="../js/fade.js" defer></script>
</head>
<body>
    <section>
        <nav>
            <a href="home.php"> <img src="../images/logo.png" class="logo"> </a>
            <ul>
                <li class="custom-link"><a href="home.php">Home</a></li>
                <li><a href="rooms.php">Rooms</a></li>
                <li><a href="team.php">Team</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
           <a href="../admin/login.php"> <img src="../images/menu.svg" class="menu-img" > </a>
        </nav>

        <div class="flex-container">
          
            <div class="content">
                <h1>Greenleaf</h1> 
                <br>
                <h2>Online Management Hotel Reservation</h2>
                <p>Welcome to the Greenleaf Hotel, where seamless booking and efficient management meet at the homepage!</p>
                <a href="rooms.php" class="btn">Reservation</a>
            </div>
            
              <div class="slideshow-container">
                <div class="mySlides fade">
                    <img src="../images/1.jpg" alt="Image 1">
                </div>
                <div class="mySlides fade">
                    <img src="../images/2.jpg" alt="Image 2">
                </div>
                <div class="mySlides fade">
                    <img src="../images/3.jpg" alt="Image 3">
                </div>
            </div>   
        </div>
    </section>
</body>
</html>