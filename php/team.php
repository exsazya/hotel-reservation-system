<?php
include('connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greenleaf</title>
    <link rel="stylesheet" href="../css/team.css">
    <script src="../js/fade.js" defer></script>

</head>
<body>
    <section>
        <nav>
        <a href="home.php"> <img src="../images/logo.png" class="logo"> </a>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="rooms.php">Rooms</a></li>
                <li class="custom-link"><a href="team.php">Team</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            <a href="../admin/login.php"> <img src="../images/menu.svg" class="menu-img" > </a>
        </nav>

        

        <div class="profile-cards">
            <div class="profile-card">
                <img src="../images/profile.jpg" alt="Profile Picture" class="profile-image">
                <div class="profile-name">Franklin Carranza</div>
                <div class="profile-description">Lead Programmer</div>
                <a href="https://www.facebook.com/franklinahr" class="social-link">Visit Profile</a>
            </div>

            <div class="profile-card">
                <img src="../images/profile-2.jpg" alt="Profile Picture" class="profile-image">
                <div class="profile-name">Alaiza <br> Ledde</div>
                <div class="profile-description">Scrum Master</div>
                <a href="https://www.facebook.com/laiza.leds" class="social-link">Visit Profile</a>
            </div>

            <div class="profile-card">
                <img src="../images/profile-3.jfif" alt="Profile Picture" class="profile-image">
                <div class="profile-name">John Mullen Ganitano</div>
                <div class="profile-description">Web Developer</div>
                <a href="https://www.facebook.com/johnmullen.ganitano" class="social-link">Visit Profile</a>
            </div>

            <div class="profile-card">
                <img src="../images/profile-4.jpg" alt="Profile Picture" class="profile-image">
                <div class="profile-name">Yasmien Tabadero</div>
                <div class="profile-description">Document Analysis</div>
                <a href="https://www.facebook.com/yassysexy" class="social-link">Visit Profile</a>
            </div>

              <div class="profile-card">
                <img src="../images/profile-5.jpg" alt="Profile Picture" class="profile-image">
                <div class="profile-name">Christian Paul Napune</div>
                <div class="profile-description">Web Developer</div>
                <a href="https://www.facebook.com/smokemire309" class="social-link">Visit Profile</a>
            </div>
        </div>
          
            
        
    </section>
    
</body>
</html>