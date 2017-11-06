<?php

require '/var/www/vendor/autoload.php';
include '/var/www/redshift-config2.php';

use Aws\S3\S3Client;
use Aws\Rekognition\RekognitionClient;


$json = json_decode($input);
$facebook_id = isset($json->facebook_id) ? $json->facebook_id : "";

if ($facebook_id == "") {
    return array("error" => 1, "message" => "facebook_id is required");
}

$picture = file_get_contents("https://graph.facebook.com/v2.10/$facebook_id/picture");

$s3ClientS3 = new S3Client(array(
    'credentials' => array(
        'key' => $aws_access_key_id,
        'secret' => $aws_secret_access_key
    ),
    "region" => "us-east-1",
    "version" => "2006-03-01"
));

$resultS3 = $s3ClientS3->putObject(array(
    'Bucket' => "alegrium-www",
    'Key'    => "gazillionaire/images/profile/{$facebook_id}.jpg",
    'Body'   => $picture
));

//var_dump($result);


$rekognitionClient = new RekognitionClient(array(
    'credentials' => array(
        'key' => $aws_access_key_id,
        'secret' => $aws_secret_access_key
    ),
    "region" => "us-east-1",
    "version" => "2016-06-27"
));

$resultRek = $rekognitionClient->detectLabels([
    'Image' => [
        //'Bytes' => $picture,
        'S3Object' => [
            'Bucket' => 'alegrium-www',
            'Name' => "gazillionaire/images/profile/skateboard_resized.jpg",
        ],
    ]
]);

return $resultRek->Labels;