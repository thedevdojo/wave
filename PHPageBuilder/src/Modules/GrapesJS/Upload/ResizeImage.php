<?php

namespace PHPageBuilder\Modules\GrapesJS\Upload;

use Exception;

/**
 * Class for resizing images.
 *
 * Credits: https://github.com/brunoribeiro94/php-upload
 *
 * Can resize to:
 * - exact image size
 * - max width size while maintaining aspect ratio
 * - max height size while maintaining aspect ratio
 * - automatic dimensions maintaining aspect ratio
 *
 * @package PHPageBuilder\Modules\GrapesJS\Upload
 */
class ResizeImage {

    /**
     * Image extension.
     *
     * @var string
     */
    protected $ext;

    /**
     * Created image.
     *
     * @var string
     */
    protected $image;

    /**
     * Name of new image.
     *
     * @var string
     */
    protected $newImage;

    /**
     * Original image width.
     *
     * @var integer
     */
    protected $origWidth;

    /**
     * Original image height.
     *
     * @var integer
     */
    protected $origHeight;

    /**
     * New image width.
     *
     * @var integer
     */
    protected $resizeWidth;

    /**
     * New image height.
     *
     * @var integer
     */
    protected $resizeHeight;

    /**
     * Class constructor requires to send through the image filename.
     *
     * @param string $filename          filename of the image you want to resize
     * @throws Exception
     */
    public function __construct($filename) {
        if (file_exists($filename)) {
            $this->setImage($filename);
        } else {
            throw new Exception('Image ' . $filename . ' can not be found, try another image.');
        }
    }

    /**
     * Set the image variable by using image create.
     *
     * @param string $filename          the image filename
     * @throws Exception
     */
    protected function setImage($filename) {
        $size = getimagesize($filename);
        $this->ext = $size['mime'];

        switch ($this->ext) {
            // Image is a JPG
            case 'image/jpg':
            case 'image/jpeg':
                // create a jpeg extension
                $this->image = imagecreatefromjpeg($filename);
                break;

            // Image is a GIF
            case 'image/gif':
                $this->image = @imagecreatefromgif($filename);
                break;

            // Image is a PNG
            case 'image/png':
                $this->image = @imagecreatefrompng($filename);
                break;

            // Mime type not found
            default:
                throw new Exception("File is not an image, please use another file type.", 1);
        }

        $this->origWidth = imagesx($this->image);
        $this->origHeight = imagesy($this->image);
    }

    /**
     * Save the image as the image type the original image was.
     *
     * @param string $savePath          the path to store the new image
     * @param string $imageQuality      the quality level of image to create
     */
    public function saveImage($savePath, $imageQuality = "100") {
        switch ($this->ext) {
            case 'image/jpg':
            case 'image/jpeg':
                // Check PHP supports this file type
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->newImage, $savePath, $imageQuality);
                }
                break;

            case 'image/gif':
                // Check PHP supports this file type
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->newImage, $savePath);
                }
                break;

            case 'image/png':
                $invertScaleQuality = 9 - round(($imageQuality / 100) * 9);

                // Check PHP supports this file type
                if (imagetypes() & IMG_PNG) {
                    imagepng($this->newImage, $savePath, $invertScaleQuality);
                }
                break;
        }

        imagedestroy($this->newImage);
    }

    /**
     * Resize the image to the given dimensions.
     *
     * @param integer $width            max width of the image
     * @param integer $height           max height of the image
     * @param string $resizeOption      scale option for the image
     */
    public function resizeTo($width, $height, $resizeOption = 'default') {
        switch (strtolower($resizeOption)) {
            case 'exact':
                $this->resizeWidth = $width;
                $this->resizeHeight = $height;
                break;
            case 'maxwidth':
                $this->resizeWidth = $width;
                $this->resizeHeight = $this->resizeHeightByWidth($width);
                break;
            case 'maxheight':
                $this->resizeWidth = $this->resizeWidthByHeight($height);
                $this->resizeHeight = $height;
                break;
            case 'proportionally':
                $ratio_orig = $this->origWidth / $this->origHeight;
                $this->resizeWidth = $width;
                $this->resizeHeight = $height;
                if ($width / $height > $ratio_orig)
                    $this->resizeWidth = $height * $ratio_orig;
                else
                    $this->resizeHeight = $width / $ratio_orig;
                break;
            default:
                if ($this->origWidth > $width || $this->origHeight > $height) {
                    if ($this->origWidth > $this->origHeight) {
                        $this->resizeHeight = $this->resizeHeightByWidth($width);
                        $this->resizeWidth = $width;
                    } else if ($this->origWidth < $this->origHeight) {
                        $this->resizeWidth = $this->resizeWidthByHeight($height);
                        $this->resizeHeight = $height;
                    } else {
                        $this->resizeWidth = $width;
                        $this->resizeHeight = $height;
                    }
                } else {
                    $this->resizeWidth = $width;
                    $this->resizeHeight = $height;
                }
                break;
        }

        $this->newImage = imagecreatetruecolor($this->resizeWidth, $this->resizeHeight);
        if ($this->ext == "image/gif" || $this->ext == "image/png") {
            imagealphablending($this->newImage, false);
            imagesavealpha($this->newImage, true);
            $transparent = imagecolorallocatealpha($this->newImage, 255, 255, 255, 127);
            imagefilledrectangle($this->newImage, 0, 0, $this->resizeWidth, $this->resizeHeight, $transparent);
        }
        imagecopyresampled($this->newImage, $this->image, 0, 0, 0, 0, $this->resizeWidth, $this->resizeHeight, $this->origWidth, $this->origHeight);
    }

    /**
     * Get the resized height from the width maintaining aspect ratio.
     *
     * @param integer $width        max image width
     * @return float
     */
    protected function resizeHeightByWidth($width) {
        return floor(($this->origHeight / $this->origWidth) * $width);
    }

    /**
     * Get the resized width from the height maintaining aspect ratio.
     *
     * @param int $height           max image height
     * @return float
     */
    protected function resizeWidthByHeight($height) {
        return floor(($this->origWidth / $this->origHeight) * $height);
    }
}
