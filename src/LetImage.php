<?php

namespace EasyImage\Core;



use EasyImage\Core\Interfaces\ImageInterface;

/**
 * @author koroph<yjk@outlook.fr>
 *
 * Class LetImage
 * @package Koroph\Core
 */
class LetImage implements ImageInterface
{
    /**
     * @var resource gb
     */
    private $_src;
    /**
     * @var resource gb
     */
    private $_tempon;
    /**
     * @var string
     */
    private $_type;
    /**
     * @var string
     */
    private $_extension;
    /**
     * @var int|float
     */
    private $_width;
    /**
     * @var int|float
     */
    private $_height;


    /**
     * @param string $FileName
     * @return LetImage
     */
    public function src(string $FileName): ?self
    {
       if (file_exists($FileName)){
           $type = explode('/', getimagesize($FileName)['mime'])[0];
           if ($type !== 'image') {
               trigger_error('it is not file', E_USER_ERROR);
           } else {
               [$width, $height] = getimagesize($FileName);
               $this->setType($type);
               $this->setExtension(image_type_to_extension(getimagesize($FileName)[2]));
               $this->setWidth($width);
               $this->setHeight($height);

               if ($this->getExtension() == '.jpeg')
                   $this->setSrc(@imagecreatefromjpeg($FileName));
               elseif ($this->getExtension() == '.png')
                   $this->setSrc(imagecreatefrompng($FileName));
               elseif ($this->getExtension() == '.gif')
                   $this->setSrc(imagecreatefromgif($FileName));

           }
           return $this;
       }else{
           trigger_error("File do not found line ".__LINE__,E_USER_ERROR);
           return null;
       }
    }

    /**
     * @param $width
     * @param $height
     * @return LetImage
     */
    public function resize($width, $height): self
    {
        $this->setTempon(imagecreatetruecolor($width, $height));
        imagecopyresampled($this->getTempon(), $this->getSrc(),
            0, 0, 0, 0, $width, $height, $this->_width, $this->_height);
        return $this;
    }

    /**
     * @param $angle
     * @return LetImage
     */
    public function rotate($angle): self
    {
        $this->setTempon(imagerotate($this->getSrc(), $angle, 0));
        if ($this->getTempon() === false) {
            trigger_error('an error occurred at the line' . __FILE__, E_USER_ERROR);
            exit();
        }
        return $this;
    }

    /**
     * @param string $extension
     * @return LetImage
     */
    public function convert(string $extension): self
    {
        $TYPE = ['.png', '.gif', '.jpeg'];
        if (in_array($_extension = strtolower('.' . $extension), $TYPE)) {
            $this->setExtension($_extension);
            $this->setTempon(imagecreatetruecolor($this->getWidth(), $this->getHeight()));
            imagecopy($this->getTempon(), $this->getSrc(),
                0, 0, 0, 0, $this->getWidth(), $this->getHeight());
            return $this;
        } else {
            trigger_error('an error occurred at the line' . __FILE__, E_USER_ERROR);
            exit(404);
        }

    }

    public function copy()
    {
        $this->setTempon(imagecreatetruecolor($this->getWidth(), $this->getHeight()));
        $is_copy = imagecopy($this->getTempon(),
            $this->getSrc(), 0, 0, 0, 0, $this->getWidth(), $this->getHeight());
        if ($is_copy === false) {
            trigger_error('an error occurred at the line' . __FILE__);
            die();
        }
    }

    /**
     * @param string|null $FileName
     * @param string $prefix
     * @param string $dir
     * @return bool
     */
    public function save(?string $FileName = null, string $prefix = 'kh_', string $dir = './')
    {
        $response = false;
        if (is_null($FileName)) {
            $FileName = substr(uniqid('df_'), 0, 10);
        } else {
            $FileName =$prefix . $FileName;
        }
        // create path
        $path =  $dir.$FileName . $this->getExtension();
        if ($this->getExtension() == '.jpeg') {
            $response = imagejpeg($this->getTempon(), $path);
        } elseif ($this->getExtension() == '.png') {
            $response = imagepng($this->getTempon(), $path);
        } elseif ($this->getExtension() == '.gif') {
            $response = imagegif($this->getTempon(), $path);
        }
        // destroy image
        imagedestroy($this->getTempon());
        imagedestroy($this->getSrc());

        return $path; // finally response
    }



    /**
     * @return mixed
     */
    private function getSrc()
    {
        return $this->_src;
    }

    /**
     * @param mixed $src
     */
    private function setSrc($src): void
    {
        $this->_src = $src;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * @param mixed $type
     */
    private function setType($type): void
    {
        $this->_type = $type;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->_extension;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension): void
    {
        $this->_extension = $extension;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width): void
    {
        $this->_width = $width;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height): void
    {
        $this->_height = $height;
    }

    /**
     * @return mixed
     */
    private function getTempon()
    {
        return $this->_tempon;
    }

    /**
     * @param mixed $tempon
     */
    private function setTempon($tempon): void
    {
        $this->_tempon = $tempon;
    }

    public function remove():bool
    {
        return unlink($this->_type);
    }
}
