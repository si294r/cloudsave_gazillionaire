<?php

$json = json_decode($input);

if (is_file("data/server_time.json")) {
    $content = file_get_contents("data/server_time.json");
    $data = json_decode($content, true);
} else {
    $data['server_time'] = gmdate('Y-m-d H:i:s');
    $data['update_time'] = $data['server_time'];
}

return $data;


