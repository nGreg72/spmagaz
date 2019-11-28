<?php
/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Modified by: Miguel Fermín
* Based in: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
* 
* This program is free software; you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 2 
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details: 
* http://www.gnu.org/licenses/gpl.html
*/

class SimpleImage {
   
	var $image;
	var $image_type;

	function __construct($filename = null){
		if(!empty($filename)){
			$this->load($filename);
		}
	}
	
	function load($filename) {
		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		if( $this->image_type == IMAGETYPE_JPEG ) {
			$this->image = imagecreatefromjpeg($filename);
		} elseif( $this->image_type == IMAGETYPE_GIF ) {
			$this->image = imagecreatefromgif($filename);
		} elseif( $this->image_type == IMAGETYPE_PNG ) {
			$this->image = imagecreatefrompng($filename);
		} else {
			throw new Exception("The file you're trying to open is not supported");
		}
		
	}
	
	function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
		if( $image_type == IMAGETYPE_JPEG ) {
			imagejpeg($this->image,$filename,$compression);
		} elseif( $image_type == IMAGETYPE_GIF ) {
			imagegif($this->image,$filename);         
		} elseif( $image_type == IMAGETYPE_PNG ) {
			imagepng($this->image,$filename);
		}   
		if( $permissions != null) {
			chmod($filename,$permissions);
		}
	}
	
	function output($image_type=IMAGETYPE_JPEG, $quality = 80) {
		if( $image_type == IMAGETYPE_JPEG ) {
			header("Content-type: image/jpeg");
			imagejpeg($this->image, null, $quality);
		} elseif( $image_type == IMAGETYPE_GIF ) {
			header("Content-type: image/gif");
			imagegif($this->image);         
		} elseif( $image_type == IMAGETYPE_PNG ) {
			header("Content-type: image/png");
			imagepng($this->image);
		}
	}
	
	function getWidth() {
		return imagesx($this->image);
	}
	
	function getHeight() {
		return imagesy($this->image);
	}
	
	function resizeToHeight($height) {
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width,$height);
	}
	
	function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width,$height);
	}
	
	function square($size){
		$new_image = imagecreatetruecolor($size, $size);
	
		if($this->getWidth() > $this->getHeight()){
			$this->resizeToHeight($size);
			imagecopy($new_image, $this->image, 0, 0, ($this->getWidth() - $size) / 2, 0, $size, $size);
		} else {
			$this->resizeToWidth($size);
			imagecopy($new_image, $this->image, 0, 0, 0, ($this->getHeight() - $size) / 2, $size, $size);
		}
		
		$this->image = $new_image;
	}
   
	function scale($scale) {
		$width = $this->getWidth() * $scale/100;
		$height = $this->getheight() * $scale/100; 
		$this->resize($width,$height);
	}
   
	function resize($width,$height) {
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;   
	}

	function watermark ($right, $bottom, $watermark_url) {
		$new_image = imagecreatetruecolor($this->getWidth(), $this->getHeight());
		$watermark = imagecreatefrompng($watermark_url);

		$x = imagesx($watermark);
		$y = imagesy($watermark);

		imagecopy ($this->image, $watermark,
						  imagesx($this->image) - $x - $right,
						  imagesy($this->image) - $y - $bottom,
						  0, 0,
						  imagesx($watermark),
						  imagesy($watermark)
		);
	}
}


// Usage:
// Load the original image
$image = new SimpleImage('lemon.jpg');

// Resize the image to 600px width and the proportional height
$image->resizeToWidth(600);
$image->save('lemon_resized.jpg');

// Create a squared version of the image
$image->square(200);
$image->save('lemon_squared.jpg');

// Scales the image to 75%
$image->scale(75);
$image->save('lemon_scaled.jpg');

// Resize the image to specific width and height
$image->resize(80,60);
$image->save('lemon_resized2.jpg');

// Output the image to the browser:
$image->output();


