<?php

require '/var/www/vendor/autoload.php';
include '/var/www/redshift-config2.php';
include 'config.php';

use Aws\S3\S3Client;
use Aws\Rekognition\RekognitionClient;


$json = json_decode($input);
$facebook_id = isset($json->facebook_id) ? $json->facebook_id : "";

if ($facebook_id == "") {
    return array("error" => 1, "message" => "facebook_id is required");
}

/* 
 * get picture from facebook
 */
$picture = file_get_contents("https://graph.facebook.com/v2.10/$facebook_id/picture?width=1000");

/*
 * update picture to AWS S3
 */
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
    'Key'    => "$aws_s3_appname/images/profile/{$facebook_id}",
    'Body'   => $picture
));

//var_dump($result);

/*    
 * detect faces with AWS Rekognition
 */  
$rekognitionClient = new RekognitionClient(array(
    'credentials' => array(
        'key' => $aws_access_key_id,
        'secret' => $aws_secret_access_key
    ),
    "region" => "us-east-1",
    "version" => "2016-06-27"
));

$resultRek = $rekognitionClient->detectFaces([
    'Attributes' => ['ALL'],
    'Image' => [
        //'Bytes' => $picture,
        'S3Object' => [
            'Bucket' => 'alegrium-www',
            'Name' => "$aws_s3_appname/images/profile/{$facebook_id}",
        ],
    ],
//    'MaxLabels' => 5,
//    'MinConfidence' => 80,
]);

$data['Faces'] = $resultRek['FaceDetails'];
$data['CountFaces'] = count($data['Faces']);

/*
 * if detect faces null, try detect labels
 */
if ($data['CountFaces'] == 0) { 
    $resultRek = $rekognitionClient->detectLabels([
        'Attributes' => ['ALL'],
        'Image' => [
            //'Bytes' => $picture,
            'S3Object' => [
                'Bucket' => 'alegrium-www',
                'Name' => "$aws_s3_appname/images/profile/{$facebook_id}",
            ],
        ],
    //    'MaxLabels' => 5,
    //    'MinConfidence' => 80,
    ]);

    //print_r($resultRek);
    $data['Labels'] = $resultRek['Labels'];    
} else {    
    $data['Labels'] = array();    
}

return $data;