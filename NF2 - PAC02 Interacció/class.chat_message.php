<?php
require("class.pdofactory.php");
require("abstract.databoundobject.php");

class ChatMessage extends DataBoundObject {
    protected $toUserId;
    protected $fromUserId;
    protected $chatMessage;
    protected $status = 1;

    protected function DefineTableName() {
        return("chat_message");
    }

    protected function DefineRelationMap() {
        return(array(
            "id" => "chat_message_id",
            "toUserId" => "to_user_id",
            "fromUserId" => "from_user_id",
            "chatMessage" => "chat_message",
            "timestamp" => "timestamp",
            "status" => "status"
        ));
    }
    public function __construct() {
        $strDSN = "mysql:host=localhost;dbname=chat;port=3306;charset=utf8mb4";
        $objPDO = PDOFactory::GetPDO($strDSN, "root", "root",array());
        $objPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->objPDO = $objPDO;
    }

    public function InsertChatMessage($to_user_id, $from_user_id, $chat_message, $status) {
        $query = "
        INSERT INTO chat_message 
        (to_user_id, from_user_id, chat_message, status) 
        VALUES (:to_user_id, :from_user_id, :chat_message, :status)
        ";
        $statement = $this->objPDO->prepare($query);
        $success = $statement->execute([
            ':to_user_id' => $to_user_id,
            ':from_user_id' => $from_user_id,
            ':chat_message' => $chat_message,
            ':status' => $status
        ]);
        if ($success) {
            return true;
        }
        return false;
    }

    public function FetchUserChatHistory($from_user_id, $to_user_id) {
        $query = "
            SELECT * FROM chat_message 
            WHERE (from_user_id = :from_user_id AND to_user_id = :to_user_id) 
            OR (from_user_id = :to_user_id AND to_user_id = :from_user_id) 
            ORDER BY timestamp DESC
        ";
        $statement = $this->objPDO->prepare($query);
        $statement->execute([
            ':from_user_id' => $from_user_id,
            ':to_user_id' => $to_user_id
        ]);
        return $statement->fetchAll();
    }
    public function InsertGroupChatMessage($from_user_id, $chat_message, $status) {
        $query = "
        INSERT INTO chat_message 
        (from_user_id, chat_message, status, to_user_id) 
        VALUES (:from_user_id, :chat_message, :status, '0')
        ";
        $statement = $this->objPDO->prepare($query);
        return $statement->execute([
            ':from_user_id' => $from_user_id,
            ':chat_message' => $chat_message,
            ':status' => $status
        ]);
    }
    public function FetchGroupChatHistory() {
        $query = "
        SELECT * FROM chat_message 
        WHERE to_user_id = '0'  
        ORDER BY timestamp DESC
        ";
        $statement = $this->objPDO->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function UpdateChatMessageStatus($from_user_id, $to_user_id) {
        $query = "
            UPDATE chat_message 
            SET status = '0' 
            WHERE from_user_id = :from_user_id 
            AND to_user_id = :to_user_id 
            AND status = '1'
        ";
        $statement = $this->objPDO->prepare($query);
        $statement->execute([
            ':from_user_id' => $to_user_id,
            ':to_user_id' => $from_user_id
        ]);
    }

    public function CountUnseenMessages($from_user_id, $to_user_id) {
        $query = "
            SELECT * FROM chat_message 
            WHERE from_user_id = :from_user_id 
            AND to_user_id = :to_user_id 
            AND status = '1'
        ";
        $statement = $this->objPDO->prepare($query);
        $statement->execute([
            ':from_user_id' => $from_user_id,
            ':to_user_id' => $to_user_id
        ]);
        return $statement->rowCount();
    }

    public function DeleteChatMessage($chatMessageId) {
        $strQuery = "UPDATE chat_message SET status = '2' WHERE chat_message_id = :chat_message_id";
        $objStatement = $this->objPDO->prepare($strQuery);
        $objStatement->bindParam(':chat_message_id', $chatMessageId, PDO::PARAM_INT);
        $objStatement->execute();
    }

}
