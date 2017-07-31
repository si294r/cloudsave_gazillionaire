<?php

if ($IS_DEVELOPMENT == true) {
    
    $result = file_get_contents('http://alegrium5.alegrium.com/gazillionaire/cloudsave/?igettime', null, stream_context_create(
            array(
                'http' => array(
                    'method' => 'POST',
                    'header' => 'Content-Type: application/json'. "\r\n"
                    . 'x-api-key: ' . X_API_KEY_TOKEN . "\r\n",
                    'content' => '{}'
                )
            )
        )
    );

//    $json['server_time'] = $result['server_time'];
//    $json['update_time'] = $result['update_time'];
    
    try {
        $json['time'] = gmdate('Y-m-d H:i:s', (time() - strtotime($result['update_time'])) + strtotime($result['server_time']));
    } catch (Exception $ex) {
        $json['time'] = gmdate('Y-m-d H:i:s');
    }
    return $json;
    
} else {
    
    return array("time" => gmdate('Y-m-d H:i:s'));
    
}

