<?php
require ('abstract.databoundobject.php');
class Twitter extends DataBoundObject {
    protected $Url;
    protected $AuthorName;
    protected $AuthorUrl;
    protected $Html;

    protected function DefineTableName() {
        return "tweets";
    }

    protected function DefineRelationMap() {
        return array(
            "id" => "ID",
            "url" => "Url",
            "author_name" => "AuthorName",
            "author_url" => "AuthorUrl",
            "html" => "Html"
        );
    }
    public function getTweet($tweetUrl){
        $url = $tweetUrl;
        $tweet = file_get_contents($url);
        if($tweet){
            return json_decode($tweet, true);
        }
    }
}
?>

