<?php
/**
*
* @package InfinityCoreCMS
* @version $Id$
* @copyright (c) 2008 InfinityCoreCMS
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('CTRACKER_DISABLED', true);
define('IN_INFINITYCORECMS', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

$fonts_path = 'images/fonts/';
$generator_template_path = $config['avatar_generator_template_path'] . '/';

$dest_pic = request_var('cachefile', '');
$source_pic = request_var('avatarfile', '');
$source_pic_full = $source_pic . '.gif';
$text_content = stripslashes(request_var('text_content', '', true));
$text_size = request_var('text_size', 10);
$text_font = request_var('text_font', 'denmark.ttf');
$text_font = $fonts_path . $text_font;
$text_color = request_var('text_color', '#ffffff');
$text_position = request_var('text_position', 0);

$avatars_array = array('ip.gif', 'a69_02.gif', 'agreen.gif', 'aphro_lite.gif', 'aphrodite.gif', 'blue.gif', 'darkblue.gif', 'firefox.gif', 'gray.gif', 'green.gif', 'opera.gif', 'pink.gif', 'purple.gif', 'red.gif', 'sblue.gif', 'av01.gif', 'av02.gif', 'av03.gif', 'av04.gif', 'av05.gif', 'av06.gif', 'av07.gif', 'av08.gif', 'av09.gif', 'av10.gif', 'av11.gif', 'av12.gif', 'av13.gif', 'av14.gif', 'av15.gif', 'av16.gif', 'av17.gif');

if (in_array($source_pic_full, $avatars_array))
{
	$source_pic = $generator_template_path . $source_pic_full;
}
else
{
	$num = mt_rand(1, sizeof($avatars_array));
	$source_pic = $generator_template_path . $avatars_array[$num];
}

write_text($source_pic, $dest_pic, $text_content, $text_font, $text_size, $text_color, $text_position);
//write_text($source_pic, $dest_pic, $text_content, $text_font, $text_size, '#FFFFAA', $text_position);

/**
* Write text on images
*/
function write_text($source_pic, $dest_pic, $text_content, $text_font, $text_size = 10, $text_color = '#FFFFFF', $text_position = '0')
{
	$temp_pic = imagecreatefromgif($source_pic);
	list($image_width, $image_height) = getimagesize($source_pic);

	/*
	$temp_pic_empty = imagecreatetruecolor ($image_width, $image_height);
	imagealphablending($temp_pic_empty, false);
	imagecopyresampled($temp_pic_empty, $temp_pic, 0, 0, 0, 0, $image_width, $image_height, $image_width, $image_height);
	imagesavealpha($temp_pic_empty, true);
	//imagepng($im_re, 'small_redfade.png');
	$temp_pic2 = imagecreatefrompng($source_pic);
	*/

	// Calculate the centre
	for(;;)
	{
		list($left_x, , $right_x) = imagettfbbox($text_size, $text_position, $text_font, $text_content);
		$text_width = $right_x - $left_x;
		if($image_width > $text_width + 5)
		{
			break;
		}
		$text_size = $text_size - 0.5;
		if($text_size == 1)
		{
			die('Font size may not be reduced further, try to insert a shorter text');
		}
	}
	$text_padding = ($image_width - $text_width) / 2;

	$text_color = (substr($text_color, 0, 1) == '#') ? substr($text_color, 1, 6) : $text_color;
	$text_color_r = hexdec(substr($text_color, 0, 2));
	$text_color_g = hexdec(substr($text_color, 2, 2));
	$text_color_b = hexdec(substr($text_color, 4, 2));

	$text_color = imagecolorresolve($temp_pic, $text_color_r, $text_color_g, $text_color_b);
	//$text_color = imagecolorallocate($temp_pic, $text_color_r, $text_color_g, $text_color_b);
	imagettftext($temp_pic, $text_size, $text_position, $text_padding, ($image_height - ($text_size / 2)), $text_color, $text_font, $text_content);

	if($_GET['dl'])
	{
		header('Content-Disposition: attachment; filename="avatar.gif"');
	}

	/*
	header("Content-type: image/png");
	imagepng($temp_pic, $dest_pic);
	imagepng($temp_pic);
	imagedestroy($temp_pic);
	*/
	header('Content-type: image/gif');
	imagegif($temp_pic, $dest_pic);
	imagegif($temp_pic);
	imagedestroy($temp_pic);

	return true;
}

?>