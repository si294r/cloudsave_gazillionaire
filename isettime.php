<?php

$json = json_decode($input);
$data['server_time'] = isset($json->time) ? $json->time : gmdate('Y-m-d H:i:s');
$data['update_time'] = gmdate('Y-m-d H:i:s');
file_put_contents("data/server_time{$BUILD_TYPE}.json", json_encode($data));

return $data;


