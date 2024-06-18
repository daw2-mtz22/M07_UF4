<?php
include 'db.php';

try {
//Esta es la forma que he encontrado para coger todas las tablas en psgrs sin el comando
    $stmt = $objPDO->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_type='BASE TABLE'");
    $tables = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tables[] = $row['table_name'];
    }
    echo json_encode($tables);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
