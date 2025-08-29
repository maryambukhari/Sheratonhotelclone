<?php
// index.php - Homepage
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sheraton Hotels - Homepage</title>
    <style>
        /* Amazing colorful CSS with effects for images */
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); color: #333; }
        header { background: rgba(0, 0, 0, 0.8); color: white; padding: 20px; text-align: center; animation: fadeIn 2s; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .search-bar { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.2); transition: transform 0.3s; }
        .search-bar:hover { transform: scale(1.05); }
        .search-bar input, .search-bar button { padding: 10px; margin: 10px; border: none; border-radius: 5px; }
        .search-bar input { background: #f0f0f0; width: 200px; }
        .search-bar button { background: #ff5722; color: white; cursor: pointer; transition: background 0.3s; }
        .search-bar button:hover { background: #e64a19; }
        .featured { display: flex; flex-wrap: wrap; justify-content: center; margin: 20px; }
        .hotel-card { width: 300px; margin: 15px; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: transform 0.3s, box-shadow 0.3s; }
        .hotel-card:hover { transform: translateY(-10px); box-shadow: 0 8px 30px rgba(0,0,0,0.3); }
        .hotel-card img { width: 100%; height: 200px; object-fit: cover; transition: transform 0.5s; }
        .hotel-card:hover img { transform: scale(1.1); }
        .hotel-card h3 { text-align: center; color: #ff5722; margin: 10px 0; }
        .hotel-card p { padding: 0 10px; }
        .filters { margin: 20px; text-align: center; }
        .filters select, .filters input { padding: 10px; margin: 5px; border-radius: 5px; border: 1px solid #ddd; }
        @media (max-width: 768px) { 
            .search-bar { flex-direction: column; } 
            .featured { flex-direction: column; align-items: center; } 
            .hotel-card { width: 90%; }
        }
    </style>
</head>
<body>
    <header>
        <h1>Sheraton Hotels Clone</h1>
    </header>
    <div class="search-bar">
        <form id="searchForm">
            <input type="text" id="location" placeholder="Destination" required>
            <input type="date" id="checkin" required>
            <input type="date" id="checkout" required>
            <button type="submit">Search</button>
        </form>
    </div>
    <div class="filters">
        <label>Price Range:</label> <input type="range" min="50" max="500" value="100">
        <label>Rating:</label> <select><option>Any</option><option>4+</option><option>4.5+</option></select>
        <label>Amenities:</label> <input type="checkbox" value="WiFi"> WiFi <input type="checkbox" value="Pool"> Pool
    </div>
    <div class="featured">
        <?php
        $stmt = $pdo->query("SELECT * FROM hotels LIMIT 3");
        while ($hotel = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="hotel-card">
                    <img src="' . htmlspecialchars($hotel['image_url']) . '" alt="' . htmlspecialchars($hotel['name']) . '">
                    <h3>' . htmlspecialchars($hotel['name']) . '</h3>
                    <p>' . htmlspecialchars($hotel['location']) . ' - Rating: ' . $hotel['rating'] . '</p>
                    <p>' . htmlspecialchars(substr($hotel['description'], 0, 100)) . '...</p>
                  </div>';
        }
        ?>
    </div>
    <script>
        // JS for form submission and redirection
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const location = document.getElementById('location').value;
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            window.location.href = `search.php?location=${encodeURIComponent(location)}&checkin=${checkin}&checkout=${checkout}`;
        });
    </script>
</body>
</html>
