<?php
require 'class.Twitter.php';
require_once 'class.pdofactory.php';
$strDSN = "pgsql:host=localhost;dbname=twitter;port=5432;";
$objPDO = PDOFactory::GetPDO($strDSN, "postgres", "fpllefia123",array());
$objPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);;

echo '<br><br>';
$url = 'https://publish.twitter.com/oembed?url=https://twitter.com/Interior/status/507185938620219395';
$tweet = new Twitter($objPDO);
$tweetData = $tweet->getTweet($url);
if($tweetData){
    $tweet->setAuthorName($tweetData['author_name']);
    $tweet->setUrl($tweetData['url']);
    $tweet->setAuthorUrl($tweetData['author_url']);
    $tweet->setHtml(htmlspecialchars($tweetData['html']));
    $tweet->Save();

    print "Author name is " . $tweet->getAuthorName() . "<br />";
    print "Author Url is " . $tweet->getAuthorUrl() . "<br />";
    print "Html is " . $tweet->getHtml() . "<br />";
    print "Url " . $tweet->getUrl() . "<br />";
}


