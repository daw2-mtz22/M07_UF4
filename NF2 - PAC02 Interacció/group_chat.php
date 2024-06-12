<?php

//group_chat.php

include('database_connection.php');
require('class.chat_message.php');

session_start();
$chatMessage = new ChatMessage();

if($_POST["action"] == "insert_data")
{
    if($chatMessage->InsertGroupChatMessage($_SESSION["user_id"], $_POST['chat_message'], '1')) {
        echo formatMessages($chatMessage->FetchGroupChatHistory(), $_SESSION["user_id"]);
    }
}

if($_POST["action"] == "fetch_data")
{
    echo formatMessages($chatMessage->FetchGroupChatHistory(), $_SESSION["user_id"]);
}

function formatMessages($messages, $user_id) {
    $output = '<ul class="list-unstyled">';
    foreach ($messages as $message) {
        $user_name = ($message["from_user_id"] == $user_id) ? 'You' : 'Other User'; // Placeholder for actual username fetching
        $dynamic_background = ($message["from_user_id"] == $user_id) ? 'background-color:#ffe6e6;' : 'background-color:#ffffe6;';
        $chat_message = $message['status'] == '2' ? '<em>This message has been removed</em>' : htmlspecialchars($message['chat_message']);
        $output .= '<li style="border-bottom:1px dotted #ccc;padding-top:8px; padding-left:8px; padding-right:8px;' . $dynamic_background . '">
                        <b class="text-success">' . $user_name . '</b> - ' . $chat_message . ' 
                        <div align="right">
                            - <small><em>' . $message['timestamp'] . '</em></small>
                        </div>
                    </li>';
    }
    $output .= '</ul>';
    return $output;
}

?>
