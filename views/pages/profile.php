<?php 
use app\services\Page; 
use app\controllers\Auth;
?>

<!DOCTYPE html>
<html lang="ru">
<?php Page::part('head'); ?>
<link rel="stylesheet" href="/assets/css/profile.css">
<body>
    <?php Page::part('navbar'); ?>
    <section class="width-wrapper">
        <h2>Профиль</h2>
        <div class="info">
            <?php
                $user = $_SESSION['user'];
                echo "<span>".$user['full_name']."</span>";
                echo "<span>".$user['email']."</span>";
                echo "<span>".($user['category'] == Auth::ADMIN ? "Администратор" : "Обычный пользователь")."</span>";
            ?>
        </div>
    </section>
</body>
</html>