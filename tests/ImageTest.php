<?php


namespace Tests;

use EasyImage\Core\LetImage;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{


    public function testInstance():void {
        $image=new LetImage();
        $this->assertInstanceOf(LetImage::class,$image);
    }

    public function testInit(){
        $image=new LetImage();
        $image->src(image_path("user.jpg"));
        $this->assertInstanceOf(LetImage::class,$image);
    }

    public function testRotate(){
        $image=new LetImage();
        $image->src(image_path("user.jpg"));
        $image->rotate(90);
        $is_save=$image->save('rotate','df_',image_test_output());

        $this->assertTrue(file_exists($is_save));
    }

     public function testResize(){
        $image=new LetImage();
        $image->src(image_path("user.jpg"));
        $image->resize(300,750);
        $is_save=$image->save('resize','df_',image_test_output());
        $this->assertTrue(file_exists($is_save));
    }

    public function testCopy(){
        $image=new LetImage();
        $image->src(image_path("user.jpg"));
        $image->copy();
        $is_save=$image->save('copy','df_',image_test_output());
        $this->assertTrue(file_exists($is_save));
    }

   public function testConvert(){
        $image=new LetImage();
        $image->src(image_path("user.jpg"));
        $image->convert('png');
        $image->resize($image->getWidth(),$image->getHeight());
        $is_save=$image->save('convert','df_',image_test_output());
        $this->assertTrue(file_exists($is_save));
    }
}
