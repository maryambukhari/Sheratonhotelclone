<?php
// book.php - Booking System
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'];
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    // Calculate total price (simplified: price per night * nights)
    $stmt = $pdo->prepare("SELECT price FROM rooms WHERE id = ?");
    $stmt->execute([$room_id]);
    $price = $stmt->fetchColumn();
    $nights = (strtotime($check_out) - strtotime($check_in)) / (60*60*24);
    $total = $price * $nights;

    // Insert booking
    $stmt = $pdo->prepare("INSERT INTO bookings (room_id, user_name, user_email, check_in, check_out, total_price, status) VALUES (?, ?, ?, ?, ?, ?, 'confirmed')");
    $stmt->execute([$room_id, $user_name, $user_email, $check_in, $check_out, $total]);

    echo '<script>alert("Booking Confirmed!"); window.location.href = "index.php";</script>';
    exit;
}

$room_id = $_GET['room_id'] ?? 0;
$checkin = $_GET['checkin'] ?? '';
$checkout = $_GET['checkout'] ?? '';

// Fetch room details
$stmt = $pdo->prepare("SELECT r.*, h.name AS hotel_name FROM rooms r JOIN hotels h ON r.hotel_id = h.id WHERE r.id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room</title>
    <style>
        /* Amazing colorful CSS with effects */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); color: #333; }
        header { background: rgba(0, 0, 0, 0.7); color: white; padding: 20px; text-align: center; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
        .booking-form { max-width: 500px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.2); transition: all 0.3s; }
        .booking-form:hover { box-shadow: 0 8px 40px rgba(0,0,0,0.3); }
        .booking-form input { display: block; width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ddd; }
        .booking-form button { background: #4caf50; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        .booking-form button:hover { background: #388e3c; }
        .room-details { text-align: center; margin: 20px; }
        .room-details img { width: 100%; max-width: 400px; border-radius: 10px; }
        @media (max-width: 768px) { .booking-form { width: 90%; } }
    </style>
</head>
<body>
    <header>
        <h1>Book <?php echo $room['hotel_name'] . ' - ' . $room['type']; ?></h1>
    </header>
    <div class="room-details">
        <img src="<?php echo $room['image_url']; ?>" alt="<?php echo $room['type']; ?>">
        <p>Price: $<?php echo $room['price']; ?> per night | Amenities: <?php echo $room['amenities']; ?></p>
    </div>
    <div class="booking-form">
        <form method="POST">
            <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
            <input type="hidden" name="check_in" value="<?php echo $checkin; ?>">
            <input type="hidden" name="check_out" value="<?php echo $checkout; ?>">
            <input type="text" name="user_name" placeholder="Your Name" required>
            <input type="email" name="user_email" placeholder="Your Email" required>
            <button type="submit">Confirm Booking</button>
        </form>
    </div>
    <script>
        // No additional JS needed, form submits to self and redirects via PHP/JS alert
    </script>
</body>
</html>
