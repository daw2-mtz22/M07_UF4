<?php
require 'class.Twitter.php';
require_once 'class.pdofactory.php';
$strDSN = "mysql:host=localhost;dbname=twitter;port=3306;";
$objPDO = PDOFactory::GetPDO($strDSN, "root", "root",array());
$objPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);;

echo '<br><br>';
$url = 'https://publish.twitter.com/oembed?url=https://twitter.com/Interior/status/507185938620219395';
$tweet = new Twitter($objPDO);
$tweetData = $tweet->getTweet($url);
if($tweetData){
    $tweet->setAuthorName($tweetData['author_name']);
    $tweet->setUrl($tweetData['url']);
    $tweet->setAuthorUrl($tweetData['author_url']);
    $tweet->setHtml($tweetData['html']);
    //$tweet->Save();
    print "Id is " . $tweet->getId() . "<br />";
    print "Author name is " . $tweet->getAuthorName() . "<br />";
    print "Author Url is " . $tweet->getAuthorUrl() . "<br />";
    print "Html is " . $tweet->getHtml() . "<br />";
    print "Url " . $tweet->getUrl() . "<br />";
}
var_dump($tweet->getAuthorName());


