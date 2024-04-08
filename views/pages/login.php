<?php use app\services\Page; ?>

<!DOCTYPE html>
<html lang="ru">
<?php Page::part('head'); ?>
<link rel="stylesheet" href="/assets/css/auth.css">
<body>
    <?php Page::part('navbar'); ?>
    <section class="container">
        <h2>Вход</h2>
        <form class="auth" action="/auth/login" method="POST">
            <label>Email</label>
            <input type="text" name="email" required>

            <label>Пароль</label>
            <input type="password" name="password" required>

            <button type="submit">Войти</button>
        </form>
        <p>Еще нет аккаунта? <a href="/register">Зарегистрируйтесь.</a></p>
        <?php
        if (isset($_SESSION['auth'])) {
            echo "<p>".$_SESSION['auth']."</p>";
            unset($_SESSION['auth']);
        }
        ?>
    </section>
</body>
</html>