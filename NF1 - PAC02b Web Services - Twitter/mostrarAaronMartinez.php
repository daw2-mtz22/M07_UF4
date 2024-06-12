<?php
require 'class.Twitter.php';
require_once 'class.pdofactory.php';


try {
    $strDSN = "pgsql:host=localhost;dbname=twitter;port=5432;";
    $objPDO = PDOFactory::GetPDO($strDSN, "postgres", "fpllefia123",array());
    $objPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);;

    $sql = "SELECT * FROM tweets";
    $stmt = $objPDO->prepare($sql);
    $stmt->execute();

    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($events as $key => $event) {
        $event = new Twitter($objPDO, $key+1);
        $event->Load();
        print "Id is " . $event->getID() . "<br />";
        print "Author name is " . $event->getAuthorName() . "<br />";
        print "Author Url is " . $event->getAuthorUrl() . "<br />";
        print "Html is " . $event->getHtml() . "<br />";
        print "Url " . $event->getUrl() . "<br />";
    }

} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}

?>