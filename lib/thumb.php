<?php


//phpThumb.php?src=../img/uploads/$1/$5&w=$2&h=$3&zc=$4  [L]

/*
*
*   @param $main_img_obj Ц идентификатор изображени€, на которое добавл€етс€ надпись
*   @param $watermark_img_obj Ц ид. изображени€ прозрачного png8
*   @param $alpha_level Ц прозрачность (0 Ц прозрачное, 100 Ц полностью непрозрачное)
*   @return $main_img_obj - указатель изображени€
*/
class watermark3{
 
	# given two images, return a blended watermarked image
	function create_watermark( $main_img_obj, $watermark_img_obj, $alpha_level = 100 ) {
		$alpha_level	/= 100;	# convert 0-100 (%) alpha to decimal

		# calculate our images dimensions
		$main_img_obj_w	= imagesx( $main_img_obj );
		$main_img_obj_h	= imagesy( $main_img_obj );
		$watermark_img_obj_w	= imagesx( $watermark_img_obj );
		$watermark_img_obj_h	= imagesy( $watermark_img_obj );
 
		# determine center position coordinates
		$main_img_obj_min_x	= floor( ( $main_img_obj_w / 2 ) - ( $watermark_img_obj_w / 2 ) );
		$main_img_obj_max_x	= ceil( ( $main_img_obj_w / 2 ) + ( $watermark_img_obj_w / 2 ) );
		$main_img_obj_min_y	= floor( ( $main_img_obj_h / 2 ) - ( $watermark_img_obj_h / 2 ) );
		$main_img_obj_max_y	= ceil( ( $main_img_obj_h / 2 ) + ( $watermark_img_obj_h / 2 ) ); 
 
		# create new image to hold merged changes
		$return_img	= imagecreatetruecolor( $main_img_obj_w, $main_img_obj_h );
 
		# walk through main image
		for( $y = 0; $y < $main_img_obj_h; $y++ ) {
			for( $x = 0; $x < $main_img_obj_w; $x++ ) {
				$return_color	= NULL;
 
				# determine the correct pixel location within our watermark
				$watermark_x	= $x - $main_img_obj_min_x;
				$watermark_y	= $y - $main_img_obj_min_y;
 
				# fetch color information for both of our images
				$main_rgb = imagecolorsforindex( $main_img_obj, imagecolorat( $main_img_obj, $x, $y ) );
 
				# if our watermark has a non-transparent value at this pixel intersection
				# and we're still within the bounds of the watermark image
				if (	$watermark_x >= 0 && $watermark_x < $watermark_img_obj_w &&
							$watermark_y >= 0 && $watermark_y < $watermark_img_obj_h ) {
					$watermark_rbg = imagecolorsforindex( $watermark_img_obj, imagecolorat( $watermark_img_obj, $watermark_x, $watermark_y ) );
 
					# using image alpha, and user specified alpha, calculate average
					$watermark_alpha	= round( ( ( 127 - $watermark_rbg['alpha'] ) / 127 ), 2 );
					$watermark_alpha	= $watermark_alpha * $alpha_level;
 
					# calculate the color 'average' between the two - taking into account the specified alpha level
					$avg_red		= $this->_get_ave_color( $main_rgb['red'],		$watermark_rbg['red'],		$watermark_alpha );
					$avg_green	= $this->_get_ave_color( $main_rgb['green'],	$watermark_rbg['green'],	$watermark_alpha );
					$avg_blue		= $this->_get_ave_color( $main_rgb['blue'],	$watermark_rbg['blue'],		$watermark_alpha );
 
					# calculate a color index value using the average RGB values we've determined
					$return_color	= $this->_get_image_color( $return_img, $avg_red, $avg_green, $avg_blue );
 
				# if we're not dealing with an average color here, then let's just copy over the main color
				} else {
					$return_color	= imagecolorat( $main_img_obj, $x, $y );
 
				} # END if watermark

				# draw the appropriate color onto the return image
				imagesetpixel( $return_img, $x, $y, $return_color );
 
			} # END for each X pixel
		} # END for each Y pixel

		# return the resulting, watermarked image for display
		return $return_img;
 
	} # END create_watermark()

	# average two colors given an alpha
	function _get_ave_color( $color_a, $color_b, $alpha_level ) {
		return round( ( ( $color_a * ( 1 - $alpha_level ) ) + ( $color_b	* $alpha_level ) ) );
	} # END _get_ave_color()

	# return closest pallette-color match for RGB values
	function _get_image_color($im, $r, $g, $b) {
		$c=imagecolorexact($im, $r, $g, $b);
		if ($c!=-1) return $c;
		$c=imagecolorallocate($im, $r, $g, $b);
		if ($c!=-1) return $c;
		return imagecolorclosest($im, $r, $g, $b);
	} # EBD _get_image_color()


	function img_resize($src, $dest, $width, $height, $rgb = 0xFFFFFF, $quality = 100) {  
	    if (!file_exists($src)) {  
	        return false;  
	    }  
	    $size = getimagesize($src);  

	    if ($size === false) {  
	        return false;  
	    }  

	    $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));  
	    $icfunc = 'imagecreatefrom'.$format;  
	    if (!function_exists($icfunc)) {  
	        return false;  
	    }  

	    if($width==0)if($size[0]<=800)$width=$size[0];else $width=800;
  
	    $x_ratio = $width  / $size[0];  
	    $y_ratio = $height / $size[1];  
  
	    if ($height == 0) {  
  
	        $y_ratio = $x_ratio;  
	        $height  = $y_ratio * $size[1];  
  
	    } elseif ($width == 0) {  
  
	        $x_ratio = $y_ratio;  
	        $width   = $x_ratio * $size[0];  
  
	    }  
  
	    $ratio       = min($x_ratio, $y_ratio);  
	    $use_x_ratio = ($x_ratio == $ratio);  
  
	    $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);  
	    $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);  
	    $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width)   / 2);  
	    $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);  
  
	    $isrc  = $icfunc($src);  
	    //$water = imagecreatefrompng("watermark.png");
	    //$isrc = $this->create_watermark($isrc,$water,50);

	    $idest = imagecreatetruecolor($width, $height);  

	    imagefill($idest, 0, 0, $rgb);  

	    imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);  

	    //header("Content-type: application/zip");  
	    header("Content-type: image/jpeg");
	    imagejpeg($idest, $dest, $quality);
  
	    imagedestroy($isrc);  
	    imagedestroy($idest);  
  
	    return true;  
}  

}
  

$src='..'.str_replace('..','',$_GET['src']);


$width=intval($_GET['w']);
$height=intval($_GET['h']);
$filesize=@filesize($src);
$hash=md5($src.$width.$height.$filesize);

$upload_path='cache2/'.$hash{0};
if (!is_dir($upload_path)) {@mkdir($upload_path, 0777, true);}
$dest=$upload_path.'/'.$hash;
$time=3600*24;

$modif=time()-@filemtime ($dest);
if ($modif<$time)
	{
	$img=file_get_contents($dest);
	header("Content-type: image/jpeg");
	echo $img;
	exit;
	}
	else 
	{
	$watermark = new watermark3();
	if($watermark->img_resize($src, $dest, $width, $height, $rgb = 0xFFFFFF, $quality = 100)) 
		{
		$img=file_get_contents($dest);
		header("Content-type: image/jpeg");
		echo $img;
		exit;
		}
		else {
		$img=file_get_contents('../theme/sp12/images/no_photo229x190.png');
		header("Content-type: image/jpeg");
		echo $img;
		exit;
		};

	}