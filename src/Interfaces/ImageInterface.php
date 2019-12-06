<?php


namespace EasyImage\Core\Interfaces;


interface ImageInterface
{


    public function src(string $FileName);

    public function resize($width, $height);

    public function rotate($angle);

    public function convert(string $extension);

    public function copy();

    public function remove();
    public function save(?string $FileName = null, string $prefix = 'kh_', string $dir = './');


}
