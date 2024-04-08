<?php

namespace app\controllers;
use app\services\App;
use app\services\Router;

class Catalog {
    private static function select_category_id($data, $connect) {
        $stmt = $connect->prepare("INSERT INTO `category` (name) VALUES (:name)");
        $stmt->bindParam(':name', $data['category']);

        try {
            $stmt->execute();
        } catch (\PDOException) {}
        
        $stmt = $connect->prepare("SELECT id FROM `category` WHERE name = :name");
        $stmt->bindParam(':name', $data['category']);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['id'] * 1;
    }

    private static function select_product_id($data, $path, $category_id, $connect) {
        $stmt = $connect->prepare("INSERT INTO `product` (name, brand, image, description, price, sex, category_id)".
                                  " VALUES (?, ?, ?, ?, ?, ?, ?)");
                                  
        try {
            $stmt->execute(array($data['name'], $data['brand'], $path, $data['description'], $data['price'], $data['sex'], $category_id));
        } catch (\PDOException) {}

        $stmt = $connect->prepare("SELECT id FROM `product` WHERE".
                                   " name = ? and brand = ? and price = ? and sex = ? and category_id = ?");
        $stmt->execute(array($data['name'], $data['brand'], $data['price'], $data['sex'], $category_id));
        return $stmt->fetch(\PDO::FETCH_ASSOC)['id'] * 1;
    }

    public static function add($data, $files) {
        $path = 'uploads/'.time()."_".$files['image']['name'];
        if (move_uploaded_file($files['image']['tmp_name'], $path)) {
            $connect = App::get_connect();
            $category_id = self::select_category_id($data, $connect);
            $parent_id = self::select_product_id($data, $path, $category_id, $connect);
            $stmt = $connect->prepare("INSERT INTO `attribute` (parent_id, color, size, country) ".
                                      "VALUES (?, ?, ?, ?)");
            try {
                $stmt->execute(array($parent_id, $data['color'], $data['size'], $data['country']));
            } catch (\PDOException) {
                $_SESSION['admin'] = "Такой товар уже существует";
                Router::redirect('/admin');
                die();
            }
        } else {
            Router::error(500);
            die();
        }
        Router::redirect('/admin');
    }

    public static function delete($data) {
        $connect = App::get_connect();
        $stmt = $connect->prepare("DELETE FROM `product` WHERE id = :id");
        $stmt->bindParam(":id", $data['delete']);
        try {
            $stmt->execute();
        } catch (\PDOException) {
            $_SESSION['admin'] = "Такого товара нет в базе";
            Router::redirect('/admin');
            die();
        }
        Router::redirect('/catalog');
    }

    public static function filtered_query($data) {
        $visited_before = false;
        $query_string = "SELECT * FROM `product` WHERE ";
        
        if (isset($data['search-filter']) && $data['search-filter'] != null) {
            $str = trim($data['search-filter']);
            $words = explode(' ', $str);
            $words = array_unique($words);
            foreach ($words as $key => $word)
                if ($word == null)
                    unset($words[$key]);
            $i = 0;
            $count = count($words);
            foreach ($words as $word) {
                if ($i !== $count - 1)
                    $query_string .= "name REGEXP '".$word."' or ";
                else
                    $query_string .= " name REGEXP '".$word."'";
                $i++;
            }
            return $query_string;
        }
        
        if (isset($data['sex']) && $data['sex'] !== "none") {
            $query_string .= "sex = '".$data['sex']."'";
            $visited_before = true;
        }
        if (isset($data['brand']) && $data['brand'] != null) {
            if ($visited_before) {
                $query_string .= " and brand = '".$data['brand']."'";
            } else {
                $query_string .= "brand = '".$data['brand']."'";
                $visited_before = true;
            }
        }
        if (isset($data['category']) && $data['category'] !== "none") {
            $category_id = self::select_category_id($data, App::get_connect());
            if ($visited_before) {
                $query_string .= " and category_id = '".$category_id."'";   
            } else {
                $query_string .= "category_id = '".$category_id."'";
                $visited_before = true;
            }
        }
        if (isset($data['size']) && $data['size'] != null) {
            $connect = App::get_connect();
            $stmt = $connect->prepare("SELECT parent_id FROM `attribute` WHERE size = :size");
            $stmt->bindParam(":size", $data['size']);
            $stmt->execute();
            $products = implode(', ', $stmt->fetchAll(\PDO::FETCH_COLUMN));
            $products = $products == null ? "-1" : $products;
            if ($visited_before) {
                $query_string .= " and id in (".$products.")";   
            } else {
                $query_string .= "id in (".$products.")";
                $visited_before = true;
            }
        }
        if (isset($data['country']) && $data['country'] != null) {
            $connect = App::get_connect();
            $stmt = $connect->prepare("SELECT parent_id FROM `attribute` WHERE country = :country");
            $stmt->bindParam(":country", $data['country']);
            $stmt->execute();
            $products = implode(', ', $stmt->fetchAll(\PDO::FETCH_COLUMN));
            $products = $products == null ? "-1" : $products;
            if ($visited_before) {
                $query_string .= " and id in (".$products.")";   
            } else {
                $query_string .= "id in (".$products.")";
                $visited_before = true;
            }
        }

        if ($visited_before)
            return $query_string;
        else
            return null;
    }

    public static function filter($data) {
        $_SESSION['filtered_request'] = self::filtered_query($data);
        Router::redirect('/catalog');
    }
    
    public static function show($filtered_request = null, $place = null) {        
        $connect = App::get_connect();
        $stmt = $filtered_request !== null ? $connect->prepare($filtered_request) : $connect->prepare("SELECT * FROM `product`");
        $stmt->execute();
        $catalog = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if ($catalog != null) {
            echo "<section class='grid-catalog'>";
            foreach ($catalog as $product) {
                echo "<div class='product'>";
                echo    "<form class='image' action='/catalog/card/show' method='POST'>".
                        "<input type='image' name='".$product['id']."' src='".$product['image']."'></form>";
                echo    "<div class='info'>";
                echo        "<h3>".$product['name']."</h3>";
                echo        "<span'>".$product['brand']."</span>";
                if ($place === null) {
                    if (isset($_SESSION['user']))
                        echo        "<form class='buttons' action='catalog/card/add' method='POST'>";
                    else
                        echo        "<form class='buttons' action='/login' method='POST'>";
                    echo            "<span class='price'>".$product['price']."<small>₽</small></span>";
                    echo            "<button type='submit' name='cart' value='".$product['id']."'>Корзина</button>";
                    echo            "<button type='submit' name='like' value='".$product['id']."'>Избранное</button>";
                    echo        "</form>";
                } else {
                    echo        "<form class='buttons' action='catalog/card/delete' method='POST'>";
                    echo            "<span class='price'>".$product['price']."<small>₽</small></span>";
                    echo            "<button type='submit' name='".$place."' value='".$product['id']."'>Убрать</button>";
                    echo        "</form>";
                }
                echo    "</div>";
                echo "</div>";
            }
            echo "</section>";
        } else {
            echo "<h2>Товаров не нашлось</h2>";
        }
    }

    public static function add_card($data) {
        if (isset($data['cart']))
            $_SESSION['user']['order'][$data['cart']] = $data['cart'];
        else if (isset($data['like']))
            $_SESSION['user']['favorites'][$data['like']] = $data['like'];
        Router::redirect('/catalog');
    }

    public static function delete_card($data) {
        if (isset($data['order'])) {
            unset($_SESSION['user']['order'][$data['order']]);
            Router::redirect('/order');
        }
        if (isset($data['favorites'])) {
            unset($_SESSION['user']['favorites'][$data['favorites']]);
            Router::redirect('/favorites');
        } 
    }

    private static function parse_id($data) {
        return explode('_', key($data))[0];
    }
    public static function show_card($data) {
        $id = self::parse_id($data);
        $connect = App::get_connect();
        $stmt = $connect->prepare("SELECT * FROM `product` WHERE id = :id");
        $stmt->bindParam(":id", $id);
        try {
            $stmt->execute();
        } catch (\PDOException) {
            Router::error(500);
        }
        $product = $stmt->fetch(\PDO::FETCH_ASSOC);
        $_SESSION['product'] = $product;

        $stmt = $connect->prepare("SELECT * FROM `attribute` WHERE parent_id = :id");
        $stmt->bindParam(":id", $id);
        try {
            $stmt->execute();
        } catch (\PDOException) {
            Router::error(500);
        }
        $attributes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $_SESSION['attributes'] = $attributes;

        Router::redirect('/product');
    }
}

?>