<?php
require ('class.PDOFactory.php');
try{
    $strDSN = "pgsql:host=localhost;dbname=dvdrental;port=5432;";
    $objPDO  = PDOFactory::GetPDO($strDSN, "postgres", "fpllefia123",array());
    $objPDO ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);;
} catch (PDOException $e) {
    echo "Connexió fallida: " . $e->getMessage();
    exit;
}
?>
