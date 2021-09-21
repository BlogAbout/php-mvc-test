<?php
class User {
    public static function register($name, $email, $password) {
        $db = Db::getConnection();
        $sql = 'INSERT INTO `user` (`name`, `email`, `password`, `role`) VALUES (:name, :email, :password, "")';

        $query = $db->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);

        return $query->execute();
    }

    public static function edit($id, $name, $password) {
        $db = Db::getConnection();
        $sql = '
            UPDATE `user`
            SET `name` = :name, password = :password
            WHERE `id` = :id
        ';

        $query = $db->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);

        return $query->execute();
    }

    /**
     * Checking user name on more 2 chars
     * @param $name
     * @return bool
     */
    public static function checkName($name) {
        if (strlen($name) >= 2)
            return true;

        return false;
    }

    /**
     * Checking user password on more 6 chars
     * @param $password
     * @return bool
     */
    public static function checkPassword($password) {
        if (strlen($password) >= 6)
            return true;

        return false;
    }

    /**
     * Checking user email on Email pattern
     * @param $email
     * @return bool
     */
    public static function checkEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
            return true;

        return false;
    }

    /**
     * Checking user email on exists
     * @param $email
     * @return bool
     */
    public static function checkEmailExists($email) {
        $db = Db::getConnection();
        $sql = "SELECT COUNT(*) FROM `user` WHERE `email` = :email";
        $query = $db->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        if ($query->fetchColumn())
            return true;

        return false;
    }

    /**
     * Checking user by email and password
     * @param $email
     * @param $password
     * @return false|mixed
     */
    public static function checkUserData($email, $password) {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM `user` WHERE `email` = :email AND `password` = :password';
        $query = $db->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch();
        if ($user)
            return $user['id'];

        return false;
    }

    /**
     * Member user in session
     * @param $userId
     */
    public static function auth($userId) {
        $_SESSION['user'] = $userId;
    }

    public static function checkLogged() {
        if (isset($_SESSION['user']))
            return $_SESSION['user'];

        header('Location: /user/login');
        return false;
    }

    public static function isGuest() {
        if (isset($_SESSION['user']))
            return false;

        return true;
    }

    public static function getUserById($id) {
        if ($id) {
            $db = Db::getConnection();
            $sql = 'SELECT * FROM `user` WHERE `id` = :id';
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute();

            return $query->fetch();
        }
    }
}