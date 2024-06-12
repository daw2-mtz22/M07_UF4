<?php

//insert_chat.php

require('class.chat_message.php');

session_start();

if (isset($_POST['to_user_id'], $_POST['chat_message'])) {
    $chatMessage = new ChatMessage();
    $output = $chatMessage->InsertChatMessage($_POST['to_user_id'], $_SESSION['user_id'], $_POST['chat_message'], '1');

    if ($output) {
        $chatHistory = $chatMessage->FetchUserChatHistory($_SESSION['user_id'], $_POST['to_user_id']);
        $output = '<ul class="list-unstyled">';
        foreach ($chatHistory as $message) {
            $user_name = ($message['from_user_id'] == $_SESSION['user_id']) ? '<b class="text-success">You</b>' : '<b class="text-danger">Other</b>';
            $background = ($message['from_user_id'] == $_SESSION['user_id']) ? 'background-color:#ffe6e6;' : 'background-color:#ffffe6;';
            $chat_message = $message['status'] == '2' ? '<em>This message has been removed</em>' : $message['chat_message'];
            $output .= '<li style="border-bottom:1px dotted #ccc; padding-top:8px; padding-left:8px; padding-right:8px; ' . $background . '">
                        <p>' . $user_name . ' - ' . $chat_message . '
                            <div align="right">
                                - <small><em>' . $message['timestamp'] . '</em></small>
                            </div>
                        </p>
                    </li>';
        }
        $output .= '</ul>';
        $chatMessage->UpdateChatMessageStatus($_POST['to_user_id'], $_SESSION['user_id']);

        echo $output;
    } else {
        echo "Error inserting message.";
    }
} else {
    echo "Required data is missing.";
}

?>
