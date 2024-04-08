<?php

use app\services\Router;
use app\controllers\Auth;
use app\controllers\Catalog;

Router::page('/login', 'login', Auth::GUEST);
Router::page('/register', 'register', Auth::GUEST);
Router::page('/catalog', 'catalog', Auth::GUEST);
Router::page('/profile', 'profile', auth::COMMON);
Router::page('/order', 'order', Auth::COMMON);
Router::page('/favorites', 'favorites', Auth::COMMON);
Router::page('/admin', 'admin', Auth::ADMIN);
Router::page('/product', 'product', Auth::GUEST);

Router::post('/auth/login', Auth::class, 'login', true);
Router::post('/auth/register', Auth::class, 'register', true);
Router::post('/auth/logout', Auth::class, 'logout');

Router::post('/catalog/add', Catalog::class, 'add', true, true);
Router::post('/catalog/delete', Catalog::class, 'delete', true);
Router::post('/catalog/filter', Catalog::class, 'filter', true);
Router::post('/catalog/card/add', Catalog::class, 'add_card', true);
Router::post('/catalog/card/delete', Catalog::class, 'delete_card', true);
Router::post('/catalog/card/show', Catalog::class, 'show_card', true);

Router::enable();

?>