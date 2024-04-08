<?php use app\services\Page; ?>

<!DOCTYPE html>
<html lang="ru">
<?php Page::part('head'); ?>
<link rel="stylesheet" href="/assets/css/auth.css">
<body>
    <?php Page::part('navbar'); ?>
    <div class="container">
        <h2>Регистрация</h2>
        <form class="auth" action="/auth/register" method="POST">
            <label>Как вас зовут</label>
            <input type="text" name="full_name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Пароль</label>
            <input type="password" name="password" required>

            <label>Подтвердите пароль</label>
            <input type="password" name="confirm" required>

            <button type="submit">Регистрация</button>
        </form>
        <p>Уже есть аккаунт? <a href="/login">Войдите.</a></p>
        <?php
        if (isset($_SESSION['auth'])) {
            echo "<p>".$_SESSION['auth']."</p>";
            unset($_SESSION['auth']);
        }
        ?>
    </div>
</body>
</html>