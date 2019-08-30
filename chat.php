<?php
$server = new Swoole\WebSocket\Server("0.0.0.0", 6060);

$server->on('open', function (Swoole\WebSocket\Server $server, Swoole\Http\Request $request) {
    //$server->push($request->fd, '欢迎进入我们');
});

// 服务器端主动向客户端发送消息
$server->on('message', function (Swoole\WebSocket\Server $server, swoole_websocket_frame $frame) {

    // $server->connections 所有已经连接过的客户端
    // 客户端消息
    $data = $frame->data;
    $ret['data'] = $data;
    // 广播群发
    foreach ($server->connections as $client) {
        // 判断是否是客户端自己本人
        if ($frame->fd == $client) {
            $ret['style'] = 'bubble me';
        } else {
            $ret['style'] = 'bubble you';
        }
        @$server->push($client, json_encode($ret, 256));
    }

});

$server->on('close', function ($ser, $fd) {
});
// 启动服务
$server->start();
