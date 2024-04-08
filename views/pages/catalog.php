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
        <section class="filters">
            <form action="/catalog/filter" method="POST">
                <div class="input-block">
                    <label>Пол</label>
                    <select name="sex">
                        <option value="none">Любой</option>
                        <option value="0">Мужской</option>
                        <option value="1">Женский</option>
                        <option value="2">Унисекс</option>
                    </select>
                </div>
                <div class="input-block">
                    <label>Категория</label>
                    <select name="category">
                        <option value="none">Любая</option>
                        <option value="Верхняя одежда">Верхняя одежда</option>
                        <option value="Худи и Свитшоты">Худи и Свитшоты</option>
                        <option value="Футболки и Майки">Футболки и Майки</option>
                        <option value="Штаны и Шорты">Штаны и Шорты</option>
                        <option value="Нижнее белье">Нижнее белье</option>
                    </select>
                </div>
                <div class="input-block">
                    <label>Бренд</label>
                    <input type="text" name="brand">
                    </select>
                </div>
                <div class="input-block">
                    <label>Размер</label>
                    <input type="number" name="size" min="30" max="60">
                </div>
                <div class="input-block">
                    <label>Страна производства</label>
                    <input type="text" name="country">
                </div>
                <button type="submit">Применить</button>
            </form>
        </section>
        <?php
        if (isset($_SESSION['filtered_request'])) {
            Catalog::show($_SESSION['filtered_request']);
            unset($_SESSION['filtered_request']);
        } else {
            Catalog::show();
        }
        ?>
    </section>
</body>
</html>