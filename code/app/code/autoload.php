<?php
spl_autoload_register(function ($name){
    $name = str_replace("\\", "/", $name);
    $file = "../app/application/$name.php";
    if (file_exists($file)) {
        require_once $file;
    }
});