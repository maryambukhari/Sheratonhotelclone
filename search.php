<?php
// search.php - Hotel Listing Page
include 'db.php';

$location = $_GET['location'] ?? '';
$checkin = $_GET['checkin'] ?? '';
$checkout = $_GET['checkout'] ?? '';

// Query for available rooms
$sql = "SELECT r.*, h.name AS hotel_name, h.image_url AS hotel_image FROM rooms r 
        JOIN hotels h ON r.hotel_id = h.id 
        WHERE h.location LIKE :location AND r.available_from <= :checkin AND r.available_to >= :checkout";
$stmt = $pdo->prepare($sql);
$stmt->execute(['location' => "%$location%", 'checkin' => $checkin, 'checkout' => $checkout]);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Listings</title>
    <style>
        /* Amazing colorful CSS with effects */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); color: #333; }
        header { background: rgba(0, 0, 0, 0.7); color: white; padding: 20px; text-align: center; animation: slideIn 1s; }
        @keyframes slideIn { from { transform: translateY(-100%); } to { transform: translateY(0); } }
        .listings { display: flex; flex-wrap: wrap; justify-content: center; margin: 20px; }
        .room-card { width: 300px; margin: 10px; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.3s; }
        .room-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.2); transform: rotate(2deg); }
        .room-card img { width: 100%; height: 200px; object-fit: cover; }
        .room-card h3 { text-align: center; color: #2196f3; }
        .sort { text-align: center; margin: 20px; }
        .sort select { padding: 10px; border-radius: 5px; background: #fff; border: 1px solid #ddd; }
        @media (max-width: 768px) { .listings { flex-direction: column; } }
    </style>
</head>
<body>
    <header>
        <h1>Available Rooms in <?php echo htmlspecialchars($location); ?></h1>
    </header>
    <div class="sort">
        <label>Sort by:</label>
        <select onchange="sortRooms(this.value)">
            <option value="price_asc">Price Low to High</option>
            <option value="price_desc">Price High to Low</option>
            <option value="rating">Best Rated</option>
        </select>
    </div>
    <div class="listings" id="roomList">
        <?php foreach ($rooms as $room): ?>
            <div class="room-card" data-price="<?php echo $room['price']; ?>" data-rating="<?php echo $room['price']; // Assuming price as proxy, adjust if rating per room ?>">
                <img src="<?php echo $room['image_url']; ?>" alt="<?php echo $room['type']; ?>">
                <h3><?php echo $room['hotel_name'] . ' - ' . $room['type']; ?></h3>
                <p>Price: $<?php echo $room['price']; ?> | Amenities: <?php echo $room['amenities']; ?></p>
                <button onclick="bookRoom(<?php echo $room['id']; ?>)">Book Now</button>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        // JS for sorting and redirection
        function sortRooms(sortBy) {
            const list = document.getElementById('roomList');
            const cards = Array.from(list.children);
            cards.sort((a, b) => {
                if (sortBy === 'price_asc') return a.dataset.price - b.dataset.price;
                if (sortBy === 'price_desc') return b.dataset.price - a.dataset.price;
                if (sortBy === 'rating') return b.dataset.rating - a.dataset.rating;
                return 0;
            });
            cards.forEach(card => list.appendChild(card));
        }

        function bookRoom(roomId) {
            const checkin = '<?php echo $checkin; ?>';
            const checkout = '<?php echo $checkout; ?>';
            window.location.href = `book.php?room_id=${roomId}&checkin=${checkin}&checkout=${checkout}`;
        }
    </script>
</body>
</html>
