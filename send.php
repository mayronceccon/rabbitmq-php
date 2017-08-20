<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$cont = 1;
while ($cont <= 100) {
    $connection = new AMQPStreamConnection('192.168.1.22', 5672, 'rabbitmq', 'rabbitmq');
    $channel = $connection->channel();
    
    $channel->queue_declare('hello', false, false, false, false);
    $dados = array(
        'id' => $cont,
        'nome' => 'Mayron',
        'sobrenome' => 'Ceccon',
        'data' => date('Y-m-d H:i:s')    
    );
    
    echo " [x] Sent data {$cont}\n";
    
    // $this->message is the array to send
    $props = array('content_type' => 'application/json');
    
    // convert the array to json
    $data = json_encode($dados);
    
    // send the resulting json data
    $msg = new AMQPMessage($data, $props);
    
    $channel->basic_publish($msg, '', 'hello');
    
    $channel->close();
    $connection->close();

    $cont++;
}
