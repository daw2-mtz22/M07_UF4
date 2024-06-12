<?php

$connectionString = "pgsql:dbname=eonetapi;host=localhost;port=5432;user=postgres;password=fpllefia123";

$urlData = parse_url($connectionString);

try {
    $conn = new PDO($connectionString);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $html = file_get_contents("https://eonet.gsfc.nasa.gov/api/v2.1/events");
    $json = json_decode($html);

    $sql = "INSERT INTO events (id, title, description, link, category_id, category_title, source_id, source_url, geometry_date, geometry_type, geometry_coordinates)
            VALUES (:id, :title, :description, :link, :category_id, :category_title, :source_id, :source_url, :geometry_date, :geometry_type, :geometry_coordinates)";
    $stmt = $conn->prepare($sql);

    $events = array_slice($json->events, 0, 5);
    foreach ($events as $event) {
        $category_id = $event->categories[0]->id;
        $category_title = $event->categories[0]->title;
        $source_id = $event->sources[0]->id;
        $source_url = $event->sources[0]->url;
        $geometry_date = date('Y-m-d H:i:s', strtotime($event->geometries[0]->date));
        $geometry_type = $event->geometries[0]->type;
        $coordinates = implode(' ', $event->geometries[0]->coordinates);

        $stmt->execute([
            ':id' => $event->id,
            ':title' => $event->title,
            ':description' => $event->description,
            ':link' => $event->link,
            ':category_id' => $category_id,
            ':category_title' => $category_title,
            ':source_id' => $source_id,
            ':source_url' => $source_url,
            ':geometry_date' => $geometry_date,
            ':geometry_type' => $geometry_type,
            ':geometry_coordinates' => $coordinates
        ]);
    }

    echo "Values inserted successfully";
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}
?>
