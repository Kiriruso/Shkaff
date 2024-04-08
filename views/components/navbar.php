<nav class="navbar">
    <a href="/catalog" class="navbar-brand">SHKAFF</a>

    <form action="catalog/filter" method="POST">
        <input
            type="text" 
            placeholder="Поиск товара" 
            onfocus="if (placeholder == 'Поиск товара') {placeholder = ''}" 
            onblur="if (placeholder == '') {placeholder = 'Поиск товара'}"
            class="navbar-search"
            name="search-filter"
        />
        <button type="submit" class="navbar-button">Найти</button>
    </form>

    <ul class="navbar-menu">
        <li><a href="/catalog">Каталог</a></li>
        <li><a href="/profile">Профиль</a></li>
        <li><a href="/order">Корзина</a></li>
        <li><a href="/favorites">Избранное</a></li>
    </ul>
    
    <?php if (!isset($_SESSION['user'])) { ?>
        <a href="/login" class="navbar-login">Войти</a>
    <?php } else { ?>
        <form action="auth/logout" method="POST">
            <button type="submit" class="navbar-button">Выйти</button>
        </form>
    <?php } ?>

    </div>
</nav>