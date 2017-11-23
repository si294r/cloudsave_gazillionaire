<?php


$facebook_id = $_GET['fb'];

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

//imagecopymerge($bg, $pp, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct);
imagecopymerge($bg, $pp, 50, 100, 0, 0, imagesx($pp), imagesy($pp), 0);

imagejpeg($bg);
imagedestroy($bg);
