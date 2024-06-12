<?php
require("class.pdofactory.php");
require("abstract.databoundobject.php");


class Login extends DataBoundObject {
    protected $id;
    protected $username;
    protected $password;

    protected function DefineTableName() {
        return "login";
    }

    protected function DefineRelationMap() {
        return array(
            "id" => "user_id",
            "username" => "username",
            "password" => "password"
        );
    }
    public function __construct() {
        $strDSN = "mysql:host=localhost;dbname=chat;port=3306;charset=utf8mb4";
        $objPDO = PDOFactory::GetPDO($strDSN, "root", "root",array());
        $objPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);;
        $this->objPDO = $objPDO;
    }

    public function LoadByUsername($username) {
        $strQuery = "SELECT * FROM " . $this->DefineTableName() . " WHERE username = :username";
        $objStatement = $this->objPDO->prepare($strQuery);
        $objStatement->bindParam(':username', $username, PDO::PARAM_STR);
        $objStatement->execute();
        $arRow = $objStatement->fetch(PDO::FETCH_ASSOC);
        if ($arRow) {
            $this->id = $arRow['user_id'];
            $this->username = $arRow['username'];
            $this->password = $arRow['password'];
            return true;
        }
        return false;
    }

    public function Login($username, $password) {
        require_once ("class.login_details.php");

        if ($this->LoadByUsername($username)) {
            if (password_verify($password, $this->password)) {
                $loginDetails = new LoginDetails();
                $_SESSION['user_id'] = $this->id;
                $_SESSION['username'] = $this->username;
                $loginDetails->UpdateLastActivity($this->id);
                return true;
            } else {
                return 'Wrong Password';
            }
        }
        return 'Wrong Username';
    }
    public function FetchAllUsers($currentUserId) {
        $query = "SELECT * FROM " . $this->DefineTableName() . " WHERE user_id != :user_id";
        $statement = $this->objPDO->prepare($query);
        $statement->execute([':user_id' => $currentUserId]);
        return $statement->fetchAll();
    }

    public static function Logout() {
        session_start();
        session_destroy();
        header('location:login.php');
        exit;
    }
    public function UsernameExists($username) {
        $query = "SELECT * FROM " . $this->DefineTableName() . " WHERE username = :username";
        $statement = $this->objPDO->prepare($query);
        $statement->execute([':username' => $username]);
        return $statement;
    }

    public function AddUser($username, $password) {
        if ($this->UsernameExists($username)->rowCount() > 0) {
            return "Username already used";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO " . $this->DefineTableName() . " (username, password) VALUES (:username, :password)";
            $statement = $this->objPDO->prepare($query);
            $success = $statement->execute([
                ':username' => $username,
                ':password' => $hashed_password
            ]);
            return $success ? "Registration Completed" : "Registration Failed";
        }
    }

}
