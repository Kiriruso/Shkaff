<?php use app\services\Page; ?>

<!DOCTYPE html>
<html lang="ru">
<?php Page::part('head'); ?>
<link rel="stylesheet" href="/assets/css/dashboard.css">
<body>
    <?php Page::part('navbar'); ?>
    <section class="container">
        <h2>Панель администратора</h2>
        <form class="dashboard" action="/catalog/add" method="POST" enctype="multipart/form-data">
            <section class="params">
                <section>
                    <label>Категория</label>
                    <select name="category" required>
                        <option value="Верхняя одежда">Верхняя одежда</option>
                        <option value="Худи и Свитшоты">Худи и Свитшоты</option>
                        <option value="Футболки и Майки">Футболки и Майки</option>
                        <option value="Штаны и Шорты">Штаны и Шорты</option>
                        <option value="Нижнее белье">Нижнее белье</option>
                    </select>
                    <label>Пол</label>
                    <select name="sex" required>
                        <option value="0">Мужской</option>
                        <option value="1">Женский</option>
                        <option value="2">Унисекс</option>
                    </select>
                    <?php
                    if (isset($_SESSION['admin'])) {
                        echo "<label>Произошла ошибка</label>";
                        echo "<label>".$_SESSION['admin']."</label>";
                        unset($_SESSION['admin']);
                    }
                    ?>
                </section>
                <section>
                    <label>Название</label>
                    <input type="text" name="name" required>
                    <label>Бренд</label>
                    <input type="text" name="brand" required>
                    <label>Изображение</label>
                    <input type="file" name="image" required>
                    <label>Описание</label>
                    <textarea name="description"></textarea>
                </section>
                <section>
                    <label>Цена</label>
                    <input type="number" name="price" min="1" max="50000" required>
                    <label>Цвет</label>
                    <input type="color" name="color" required>
                    <label>Размер</label>
                    <input type="number" name="size" min="30" max="60" required>
                    <label>Страна производства</label>
                    <input type="text" name="country" required>
                </section>
            </section>
            <section class="buttons">
                <button type="submit">Добавить</button>
                <button type="reset">Сброс</button>
            </section>
        </form>
    </section>
</body>
</html>