<?php
class Product {
    const SHOW_BY_DEFAULT = 12;

    /**
     * Returns an array of products
     * @param int $count
     * @return array
     */
    public static function getLatestProducts($count = self::SHOW_BY_DEFAULT) {
        $count = intval($count);
        $list = array();

        $db = Db::getConnection();
        $query = $db->query("
            SELECT `id`, `name`, `price`, `is_new`
            FROM `product`
            WHERE `status` = 1
            ORDER BY `id` DESC
            LIMIT $count
        ");
        $query->setFetchMode(PDO::FETCH_ASSOC);

        while($row = $query->fetch()) {
            $list[] = $row;
        }

        return $list;
    }

    /**
     * Returns an array of products by category
     * @param false $categoryId
     * @return array
     */
    public static function getProductsListByCategory($categoryId = false, $page = 1) {
        $list = array();

        if ($categoryId) {
            $page = intval($page);
            $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

            $db = Db::getConnection();
            $query = $db->query("
                SELECT `id`, `name`, `price`, `is_new`
                FROM `product`
                WHERE `status` = 1 AND `category_id` = $categoryId
                ORDER BY `id` DESC
                LIMIT " . self::SHOW_BY_DEFAULT . "
                OFFSET " . $offset
            );
            $query->setFetchMode(PDO::FETCH_ASSOC);

            while ($row = $query->fetch()) {
                $list[] = $row;
            }
        }

        return $list;
    }

    public static function getProductById($id) {
        $id = intval($id);

        if ($id) {
            $db = Db::getConnection();
            $query = $db->query("
                SELECT *
                FROM `product`
                WHERE `id` = $id
            ");
            $query->setFetchMode(PDO::FETCH_ASSOC);

            return $query->fetch();
        }

        return false;
    }

    public static function getTotalProductsInCategory($categoryId) {
        $db = Db::getConnection();
        $query = $db->query("
            SELECT count(`id`) AS count
            FROM `product`
            WHERE `status` = 1 AND `category_id` = $categoryId
        ");
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $row = $query->fetch();

        return $row['count'];
    }
}