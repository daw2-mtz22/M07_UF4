<?php


$connectionString = "pgsql:dbname=eonetapi;host=localhost;port=5432;user=postgres;password=fpllefia123";

try {
    $conn = new PDO($connectionString);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM events";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table>";
    echo "<tr><th>ID</th><th>Title</th><th>Description</th><th>Link</th><th>Category ID</th><th>Category Title</th><th>Source ID</th><th>Source URL</th><th>Geometry Date</th><th>Geometry Type</th><th>Coordinates</th></tr>";

    foreach ($events as $event) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($event['id']) . "</td>";
        echo "<td>" . htmlspecialchars($event['title']) . "</td>";
        echo "<td>" . htmlspecialchars($event['description']) . "</td>";
        echo "<td><a href='" . htmlspecialchars($event['link']) . "'>Link</a></td>";
        echo "<td>" . htmlspecialchars($event['category_id']) . "</td>";
        echo "<td>" . htmlspecialchars($event['category_title']) . "</td>";
        echo "<td>" . htmlspecialchars($event['source_id']) . "</td>";
        echo "<td><a href='" . htmlspecialchars($event['source_url']) . "'>Source</a></td>";
        echo "<td>" . htmlspecialchars($event['geometry_date']) . "</td>";
        echo "<td>" . htmlspecialchars($event['geometry_type']) . "</td>";
        echo "<td>" . htmlspecialchars($event['geometry_coordinates']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}

?>