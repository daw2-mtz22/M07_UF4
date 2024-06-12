<?php

//remove_chat.php
include ('class.chat_message.php');

if(isset($_POST["chat_message_id"]))
{
ChatMessage::deleteChatMessage($_POST["chat_message_id"]);
}

?>