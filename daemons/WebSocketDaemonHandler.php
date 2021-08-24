<?php

namespace app\daemons;

use morozovsk\websocket\Daemon;

class WebSocketDaemonHandler extends Daemon
{
    protected function onServiceMessage($connectionId, $data) {
        $data = json_decode($data);

        foreach ($this->clients as $clientId => $client) {
            $this->sendToClient($clientId, $data->message);
        }
    }
}