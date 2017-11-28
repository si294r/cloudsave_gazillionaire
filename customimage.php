<?php


$facebook_id = $_GET['fb'];
$show_pp = $_GET['pp'];
$text = isset($_GET['txt']) ? $_GET['txt'] : "60.575 Novemsexagintillion";

/* 
 * get profile picture from facebook
 */
$profile_picture = file_get_contents("https://graph.facebook.com/v2.10/$facebook_id/picture?width=100&height=100");

/*
 * get background image
 */
$background_image = file_get_contents("https://s3.amazonaws.com/alegrium-www/gazillionaire/images/share/share1.jpg");

$bg = imagecreatefromstring($background_image);
$pp = imagecreatefromstring($profile_picture);

//imagecopymerge($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
imagecopymerge($bg, $pp, 50, 100, 0, 0, imagesx($pp), imagesy($pp), 100);

// Print Text On Image
$black = imagecolorallocate($bg, 0, 0, 0);
$font_path = "/var/www/font/Roboto-Bold.ttf";
imagettftext($bg, 25, 0, 75, 300, $black, $font_path, $text);
imagettftext($bg, 25, 10, 175, 300, $black, $font_path, $text);


header('Content-type: image/jpeg');

if ($show_pp == "1") {
    imagejpeg($pp);
} else {
    imagejpeg($bg);
}

imagedestroy($bg);
imagedestroy($pp);
