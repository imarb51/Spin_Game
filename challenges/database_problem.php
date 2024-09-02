<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gozoop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to search in title
$sql1 = "SELECT title, category 
         FROM gozoop 
         WHERE title LIKE '%cbc%' 
         ORDER BY CASE WHEN category = 'test' THEN 1 WHEN category = 'profile' THEN 2 END, title";

$result1 = $conn->query($sql1);

echo "Search in title:<br>";
if ($result1->num_rows > 0) {
    while($row = $result1->fetch_assoc()) {
        echo $row["title"] . " (" . $row["category"] . ")<br>";
    }
} else {
    echo "0 results";
}

// Query to search in keyword
$sql2 = "SELECT title, category 
         FROM gozoop 
         WHERE keyword LIKE '%cbc%' 
         ORDER BY CASE WHEN category = 'test' THEN 1 WHEN category = 'profile' THEN 2 END, title";

$result2 = $conn->query($sql2);

echo "<br>Search in keyword:<br>";
if ($result2->num_rows > 0) {
    while($row = $result2->fetch_assoc()) {
        echo $row["title"] . " (" . $row["category"] . ")<br>";
    }
} else {
    echo "0 results";
}

$conn->close();
?>
