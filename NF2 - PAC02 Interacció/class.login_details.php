<?php
class LoginDetails extends DataBoundObject {

    protected $userId;
    protected $lastActivity;
    protected $isType;

    protected function DefineTableName() {
        return "login_details";
    }

    protected function DefineRelationMap() {
        return array(
            "id" => "login_details_id",
            "userId" => "user_id",
            "lastActivity" => "last_activity",
            "isType" => "is_type"
        );
    }
    public function __construct() {
        $strDSN = "mysql:host=localhost;dbname=chat;port=3306;charset=utf8mb4";
        $objPDO = PDOFactory::GetPDO($strDSN, "root", "root",array());
        $objPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);;
        $this->objPDO = $objPDO;
    }

    public function FetchLastActivity($userId) {
        $query = "SELECT last_activity FROM " . $this->DefineTableName() . " 
                  WHERE user_id = :user_id ORDER BY last_activity DESC LIMIT 1";
        $statement = $this->objPDO->prepare($query);
        $statement->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['last_activity'] : null;
    }

    public function UpdateIsTypeStatus($loginDetailsId, $isType) {
        // Validate the is_type value
        if (!in_array($isType, ['yes', 'no'])) {
            throw new InvalidArgumentException("Invalid is_type value");
        }

        $query = "UPDATE " . $this->DefineTableName() . " 
                  SET is_type = :is_type 
                  WHERE login_details_id = :login_details_id";
        $statement = $this->objPDO->prepare($query);
        return $statement->execute([
            ':is_type' => $isType,
            ':login_details_id' => $loginDetailsId
        ]);
    }

    public function UpdateLastActivity($login_details_id) {
        $now = date('Y-m-d H:i:s');
        $query = "UPDATE " . $this->DefineTableName() . " 
                  SET last_activity = :last_activity 
                  WHERE login_details_id = :login_details_id";
        $statement = $this->objPDO->prepare($query);
        $statement->bindParam(':last_activity', $now, PDO::PARAM_STR);
        $statement->bindParam(':login_details_id', $login_details_id, PDO::PARAM_INT);
        $_SESSION['login_details_id'] = $this->objPDO->lastInsertId();
        $statement->execute();
    }
}
