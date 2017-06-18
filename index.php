<?php
header('Content-type: text/html; charset=UTF-8');

if (count($_SERVER['REQUEST_METHOD']) > 0 && isset($_POST)) {
    require_once 'apiEngine.php';

    $request_uri = explode('/', $_SERVER["REQUEST_URI"]);

    $APIEngine = new APIEngine($request_uri[2]);
    if (sizeof($_FILES) > 0)
        echo $APIEngine->callApiFunction($_FILES);
    else
        echo $APIEngine->callApiFunction($_POST);
}

