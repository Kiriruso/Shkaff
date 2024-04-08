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
        <h2>Избранное</h2>
        <?php
        if (isset($_SESSION['user']['favorites']) && !empty($_SESSION['user']['favorites'])) {
            $products = implode(', ', $_SESSION['user']['favorites']);
            $query_string = "SELECT * FROM `product` WHERE id in (".$products.")";
            Catalog::show($query_string, 'favorites');
        } else {
            echo "<h2>Нет избранных товаров</h2>";
        }
        ?>
    </section>
</body>
</html>