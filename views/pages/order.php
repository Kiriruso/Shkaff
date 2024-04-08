<?php
use app\services\Page;
use app\controllers\Catalog;
?>

<!DOCTYPE html>
<html lang="ru">
<?php Page::part('head'); ?>
<link rel="stylesheet" href="/assets/css/catalog.css">
<body>
    <?php Page::part('navbar'); ?>
    <section class="width-wrapper">
        <h2>Корзина</h2>
        <?php
        if (isset($_SESSION['user']['order']) && !empty($_SESSION['user']['order'])) {
            $products = implode(', ', $_SESSION['user']['order']);
            $query_string = "SELECT * FROM `product` WHERE id in (".$products.")";
            Catalog::show($query_string, 'order');
        } else {
            echo "<h2>Нет товаров в корзине</h2>";
        }
        ?>
    </section>
</body>
</html>