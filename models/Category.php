<?php
class Category {
    public static function getCategoriesList() {
        $db = Db::getConnection();

        $list = array();

        $query = $db->query("
            SELECT `id`, `name`
            FROM `category`
            ORDER BY `sort_order` ASC
        ");

        $query->setFetchMode(PDO::FETCH_ASSOC);

        while($row = $query->fetch()) {
            $list[] = $row;
        }

        return $list;
    }
}