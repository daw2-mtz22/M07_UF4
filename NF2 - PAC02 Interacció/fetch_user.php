<?php
require("class.login.php");
require("class.login_details.php");


session_start();


if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit;
}

$login = new Login();
$users = $login->FetchAllUsers($_SESSION['user_id']);

$output = '
<table class="table table-bordered table-striped">
    <tr>
        <th width="70%">Username</th>
        <th width="20%">Status</th>
        <th width="10%">Action</th>
    </tr>
';

foreach($users as $row) {
    $status = '';
    $userDetails = new LoginDetails();
    $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
    $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
    $user_last_activity = $userDetails->FetchLastActivity($row['user_id']);
    if($user_last_activity > $current_timestamp)
    {
        $status = '<span class="label label-success">Online</span>';
    }
    else
    {
        $status = '<span class="label label-danger">Offline</span>';
    }
    $output .= '
    <tr>
        <td>'.$row['username'].'</td>
        <td>'.$status.'</td>
        <td><button type="button" class="btn btn-info btn-xs start_chat" data-touserid="'.$row['user_id'].'" data-tousername="'.$row['username'].'">Start Chat</button></td>
    </tr>
    ';
}

$output .= '</table>';

echo $output;
?>
