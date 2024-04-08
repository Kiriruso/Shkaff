<?php 
use app\controllers\Auth;
use app\services\Page;
use app\services\Router;
?>

<!DOCTYPE html>
<html lang="ru">
<?php Page::part('head'); ?>
<link rel="stylesheet" href="/assets/css/product.css">
<body>
    <?php Page::part('navbar'); ?>
    <section class="width-wrapper">
        <?php 
        if (isset($_SESSION['product']) && isset($_SESSION['attributes'])) {
            $product = $_SESSION['product'];
            $models = $_SESSION['attributes'];
            unset($_SESSION['product']);
            unset($_SESSION['attributes']);
            echo "<h2>".$product['name']."</h2>";
            echo "<div class='product'>";
            echo    "<div class='image'><img src='".$product['image']."'></div>";
            echo    "<div class='info'>";
            echo        "<span>Цена: ".$product['price']."<small>₽</small></span>";
            echo        "<span>Бренд: ".$product['brand']."</span>";

            echo        "<span>Пол: ";
            if ($product['sex'] == 0) echo "Мужской";
            if ($product['sex'] == 1) echo "Женский";
            if ($product['sex'] == 2) echo "Унисекс";
            echo        "</span>";

            echo        "<span>Размеры: ";
            foreach ($models as $attributes)
                echo    $attributes['size']." ";
            echo        "</span>";

            echo        "<span>Страны: ";
            foreach ($models as $attributes)
                echo    $attributes['country']." ";
            echo        "</span>";

            echo        "<span>Цвета: ";
            foreach ($models as $attributes)
                echo    $attributes['color']." ";
            echo        "</span>";
            
            echo        "<span class='description'>Описание: ".
                        ($product['description'] != null ? $product['description'] : "Отсутствует").
                        "</span>";
                        
            echo    "</div>";
            if (isset($_SESSION['user'])) {
                if ($_SESSION['user']['category'] == Auth::ADMIN) {
                    echo    "<form class='buttons' action='catalog/delete' method='POST'>";
                    echo    "<button type='submit' name='delete' value='".$product['id']."'>Удалить</button>";
                } else {
                    echo    "<form class='buttons' action='catalog/card/add' method='POST'>";
                    echo    "<button type='submit' name='cart' value='".$product['id']."'>Корзина</button>";
                    echo    "<button type='submit' name='like' value='".$product['id']."'>Избранное</button>";
                }
            } else {
                echo    "<form class='buttons' action='/login' method='POST'>";
                echo    "<button type='submit' name='cart' value='".$product['id']."'>Корзина</button>";
                echo    "<button type='submit' name='like' value='".$product['id']."'>Избранное</button>";
            }
            echo        "</form>";
            echo "</div>";
        } else {
            Router::redirect('/catalog');
        }
        ?>
    </section>
</body>
</html>