<?php

session_start();

use app\services\App;

require_once __DIR__."/app/services/Router.php";
require_once __DIR__."/app/services/Page.php";
require_once __DIR__."/app/services/App.php";
require_once __DIR__."/app/controllers/Auth.php";
require_once __DIR__."/app/controllers/Catalog.php";

App::start();

require_once __DIR__."/router/routes.php";

?>