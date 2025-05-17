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

// Handle delete request
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $delete_sql = "DELETE FROM reservations WHERE id = $id";
    $conn->query($delete_sql);
}

// Handle edit request
if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $room_type = $_POST['room_type'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $price = floatval($_POST['price']);

    // Update the reservation in the database
    $update_sql = "UPDATE reservations SET first_name='$first_name', last_name='$last_name', email='$email', phone='$phone', room_type='$room_type', check_in='$check_in', check_out='$check_out', price=$price WHERE id=$id";
    $conn->query($update_sql);
}

// Fetch reservations along with the price
$sql = "SELECT id, first_name, last_name, email, phone, room_type, check_in, check_out, created_at, price FROM reservations";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation List</title>
    <style>
       /* Global Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body and Layout */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* Dashboard */
.dashboard {
    width: 90%;
    max-width: 1100px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
}

/* Navbar */
.navbar {
    background-color: #a0e548;
    color: black;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 8px 8px 0 0;
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
    padding: 8px 15px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.navbar ul li a:hover {
    background-color: #1c598a;
}

/* Table */
.main-content {
    padding: 20px;
}

.table-container {
    max-height: 450px;
    overflow-y: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    border: 1px solid #ddd;
    padding: 5px 14px; /* Padding increased for better spacing */
    text-align: left;
    font-size: 14px;
}

table th {
    background-color: #a0e548;
    color:black;;
}

/* Table Actions */
td button {
    margin-right: 10px;
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 8px 12px;
    margin: 5px;
    font-size: 13px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn-edit {
    background-color: #4CAF50;
    color: white;
}

.btn-edit:hover {
    background-color: #45a049;
}

.btn-delete {
    background-color: #F44336;
    color: white;
}

.btn-delete:hover {
    background-color: #d32f2f;
}

/* Edit Form */
.edit-form {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
    width: 60%;
    width: 700px;
    height: 610px;
    z-index: 1000;
}

.edit-form h3 {
    font-size: 18px;
    margin-bottom: 15px;
}

/* Edit Form Inputs */
.edit-form input[type="text"],
.edit-form input[type="email"],
.edit-form input[type="date"],
.edit-form input[type="number"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.edit-form button {
    padding: 10px 10px;
    background-color: #2980b9;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.edit-form button:hover {
    background-color: #1c598a;
}

/* Overlay */
.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

/* Responsive Design */
@media (max-width: 768px) {
    .navbar ul {
        flex-direction: column;
        align-items: center;
    }

    table th, table td {
        padding: 8px;
        font-size: 12px;
    }

    .btn {
        padding: 6px 10px;
        font-size: 12px;
    }

    .edit-form {
        width: 90%;
    }
}

    </style>
</head>
<body>
    <div class="dashboard">
        <div class="navbar">
            <h1>Admin Panel</h1>
            <ul>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="reservation.php">Reservation</a></li>
            <li><a href="email.php">Email</a></li>

            <li><a href="../php/home.php">Logout</a></li>
            </ul>
        </div>

        <main class="main-content">
            <h2>Reservations</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Room Type</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Created At</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["first_name"] . "</td>";
                                echo "<td>" . $row["last_name"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["phone"] . "</td>";
                                echo "<td>" . $row["room_type"] . "</td>";
                                echo "<td>" . $row["check_in"] . "</td>";
                                echo "<td>" . $row["check_out"] . "</td>";
                                echo "<td>" . $row["created_at"] . "</td>";
                                echo "<td>" . $row["price"] . "</td>";
                                echo "<td>
                                        <a href='?action=delete&id=" . $row["id"] . "' class='btn btn-delete'>Delete</a>
                                        <button class='btn btn-edit' onclick='showEditForm(" . $row["id"] . ", \"" . $row["first_name"] . "\", \"" . $row["last_name"] . "\", \"" . $row["email"] . "\", \"" . $row["phone"] . "\", \"" . $row["room_type"] . "\", \"" . $row["check_in"] . "\", \"" . $row["check_out"] . "\", \"" . $row["price"] . "\")'>Edit</button>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>No reservations found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
             </div>

             <!-- Edit Form Modal -->
             <div id="editForm" class="edit-form">
                <form action="" method="POST">
                    <h3>Edit Reservation</h3>
                    <input type="hidden" name="id" id="edit-id">
                    <label for="first_name">First Name:</label>
                    <input type="text" name="first_name" id="edit-first_name" required>
                    <label for="last_name">Last Name:</label>
                    <input type="text" name="last_name" id="edit-last_name" required>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="edit-email" required>
                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" id="edit-phone" required>
                    <label for="room_type">Room Type:</label>
                    <input type="text" name="room_type" id="edit-room_type" required>
                    <label for="check_in">Check-In Date:</label>
                    <input type="date" name="check_in" id="edit-check_in" required>
                    <label for="check_out">Check-Out Date:</label>
                    <input type="date" name="check_out" id="edit-check_out" required>
                    <label for="price">Price:</label>
                    <input type="number" name="price" id="edit-price" required>
                    <button type="submit" name="edit">Save Changes</button>
                    <button type="button" onclick="closeEditForm()">Cancel</button>
                </form>
            </div>
        </main>
    </div>

    <script>
        function showEditForm(id, firstName, lastName, email, phone, roomType, checkIn, checkOut, price) {
            // Fill form with current reservation data
            document.getElementById("edit-id").value = id;
            document.getElementById("edit-first_name").value = firstName;
            document.getElementById("edit-last_name").value = lastName;
            document.getElementById("edit-email").value = email;
            document.getElementById("edit-phone").value = phone;
            document.getElementById("edit-room_type").value = roomType;
            document.getElementById("edit-check_in").value = checkIn;
            document.getElementById("edit-check_out").value = checkOut;
            document.getElementById("edit-price").value = price;

            // Show the edit form modal and overlay
            document.getElementById("editForm").style.display = "block";
            document.getElementById("overlay").style.display = "block";
        }

        function closeEditForm() {
            document.getElementById("editForm").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
