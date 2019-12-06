<?php

if (!function_exists('image_path')) {
    function image_path(string $filename)
    {

        $path = __DIR__ . "\\image\\" . $filename;
        //exit($path);
        if (is_file($path))
            return $path;
        else
            trigger_error("file do not find line" . __LINE__, E_USER_ERROR);
            exit(404);

    }

}
if (!function_exists('image_test_output')) {
    function image_test_output()
    {
        $path = __DIR__ . "\\tests\\image_end\\";
        createTestImageIfNotExist($path);
        //exit($path);
        if (is_dir($path))
            return $path;
        else
            trigger_error("file do not find line" . __LINE__, E_USER_ERROR);
        exit(404);
    }
}

function createTestImageIfNotExist(string $path){
    if (!file_exists($path)) mkdir($path);
}

