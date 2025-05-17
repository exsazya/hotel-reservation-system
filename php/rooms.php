<?php
include('connection.php');

// PayPal Sandbox API Credentials
$clientId = 'ARDl434m8uZ003KJglg3yizoji0T2zGpneupDhHHbD_NJnpyHZD9uw6iCJh241hs8vqJpxrFWBhMYoPs';
$clientSecret = 'EKP2mVZ14IGAKN7z4sY7d28Rs93HYEuef5HuRXFf5P4PNrQQdAh2OwS7xTO1HjabcCh3L_ZoJl8gGrIx';

// Function to get PayPal Access Token
function getPayPalAccessToken($clientId, $clientSecret) {
    $url = "https://api-m.sandbox.paypal.com/v1/oauth2/token";
    $headers = ["Accept: application/json", "Accept-Language: en_US"];
    $data = "grant_type=client_credentials";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$clientSecret");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result['access_token'] ?? null;
}

// Function to create a PayPal payment
function createPayPalPayment($accessToken, $price, $currency = "USD") {
    $url = "https://api-m.sandbox.paypal.com/v1/payments/payment";
    $headers = ["Content-Type: application/json", "Authorization: Bearer $accessToken"];
    $data = json_encode([
        "intent" => "sale",
        "redirect_urls" => [
            "return_url" => "https://www.paypal.com/us/home",
            "cancel_url" => "https://www.paypal.com/us/home"
        ],
        "payer" => ["payment_method" => "paypal"],
        "transactions" => [
            ["amount" => ["total" => $price, "currency" => $currency], "description" => "Room Reservation Payment"]
        ]
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Handle reservation form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $room_type = $_POST['room_type'] ?? '';
    $check_in = $_POST['check_in'] ?? '';
    $check_out = $_POST['check_out'] ?? '';

    $prices = ['deluxe' => 550, 'executive' => 750, 'standard' => 1000, 'family' => 2000];
    $price = $prices[$room_type] ?? 0;

    // Insert reservation into the database
    $sql = "INSERT INTO reservations (first_name, last_name, email, phone, room_type, check_in, check_out, price)
            VALUES ('$first_name', '$last_name', '$email', '$phone', '$room_type', '$check_in', '$check_out', '$price')";
    if ($conn->query($sql) === TRUE) {
        // Create PayPal payment
        $accessToken = getPayPalAccessToken($clientId, $clientSecret);
        if ($accessToken) {
            $payment = createPayPalPayment($accessToken, $price, "USD");
            if (isset($payment['links'])) {
                foreach ($payment['links'] as $link) {
                    if ($link['rel'] === "approval_url") {
                        header("Location: " . $link['href']); // Redirect to PayPal for payment
                        exit;
                    }
                }
            } else {
                echo "Error creating PayPal payment.";
            }
        } else {
            echo "Error obtaining PayPal access token.";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greenleaf Reservation</title>
    <link rel="stylesheet" href="../css/rooms.css">
    <script src="../js/fade.js" defer></script>
</head>
<body>
    <section>
        <nav>
            <a href="home.php"><img src="../images/logo.png" class="logo"></a>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li class="custom-link"><a href="rooms.php">Rooms</a></li>
                <li><a href="team.php">Team</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            <a href="../admin/login.php"><img src="../images/menu.svg" class="menu-img"></a>
        </nav>

        <div class="flex-container">
            <div class="room-card">
                <img src="../images/r1.jpg" alt="Room Image" class="room-image">
                <h2 class="room-title">Deluxe Room</h2>
                <p class="room-description">A spacious room with a beautiful view, perfect for relaxation.</p>
                <p class="room-price">₱550 per night</p>
                <button class="reservation-button" onclick="openModal('deluxe')">Reserve Now</button>
            </div>

            <div class="room-card">
                <img src="../images/r2.jpg" alt="Room Image" class="room-image">
                <h2 class="room-title">Executive Suite</h2>
                <p class="room-description">Luxurious suite with premium amenities for a comfortable stay.</p>
                <p class="room-price">₱750 per night</p>
                <button class="reservation-button" onclick="openModal('executive')">Reserve Now</button>
            </div>

            <div class="room-card">
                <img src="../images/r3.jpg" alt="Room Image" class="room-image">
                <h2 class="room-title">Standard Room</h2>
                <p class="room-description">Cozy room ideal for budget-conscious travelers.</p>
                <p class="room-price">₱1000 per night</p>
                <button class="reservation-button" onclick="openModal('standard')">Reserve Now</button>
            </div>

            <div class="room-card">
                <img src="../images/r4.jpg" alt="Room Image" class="room-image">
                <h2 class="room-title">Family Suite</h2>
                <p class="room-description">Spacious suite designed for families with kids.</p>
                <p class="room-price">₱2000 per night</p>
                <button class="reservation-button" onclick="openModal('family')">Reserve Now</button>
            </div>
        </div> <!-- End of Flex Container -->

        <!-- Reservation Modal -->
        <div id="reservationModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">Reservation Form</div>
                <form id="reservationForm" method="POST">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="tel" name="phone" placeholder="Phone Number" required>
                    <input type="date" name="check_in" required>
                    <input type="date" name="check_out" required>
                    <input type="hidden" name="room_type" id="roomType" value="">
                    <div class="modal-footer">
                        <button type="button" class="close-btn" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="reserve-btn">Reserve</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Display Success or Error Messages -->
        <?php if (!empty($success_message)): ?>
            <div class="message success" id="popupMessage"><?php echo $success_message; ?></div>
        <?php elseif (!empty($error_message)): ?>
            <div class="message error" id="popupMessage"><?php echo $error_message; ?></div>
        <?php endif; ?>

    </section>

    <script>
        // Function to open the reservation modal and set the room type
        function openModal(roomType) {
            document.getElementById('roomType').value = roomType; // Set the room type in the hidden input field
            document.getElementById('reservationModal').style.display = 'block'; // Show the modal
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('reservationModal').style.display = 'none'; // Hide the modal
        }
    </script>
</body>
</html>
