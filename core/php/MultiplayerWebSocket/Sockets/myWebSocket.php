<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

//SELFCAST SERVICES
define('REQUEST_PLAYER_NUMBER', 10);

class myWebSocket implements MessageComponentInterface {
    protected $clients;
    protected $players;
    
    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->players = [];
    }
    
    public function onOpen(ConnectionInterface $conn) {
         $this->clients->attach($conn);
         
        $this->attachPlayer($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg, true);
        if($this->isMultiplayerEvent( $data['event'])){
            foreach ($this->clients as $client) {
                if ($from !== $client) {
                    // The sender is not the receiver, send to each client connected
                    $client->send($msg);
                } 
            }
        }
        
        if($this->isServerServiceRequest($data['event'])){
            switch($data['event']){
                
                case REQUEST_PLAYER_NUMBER:
                    $data['event'] = REQUEST_PLAYER_NUMBER;
                    $data['playerName'] = $this->getPlayerNumber($from->resourceId);
                    $data = json_encode($data);
                    $from->send($data);
            }
        }
        
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
    
    
    private function isMultiplayerEvent($event){
        return (0 <= $event) && ( $event <= 9); //Check multiplayer.js defines
    }
    
    private function isServerServiceRequest($event){
        return (10 <= $event) && ( $event); //Check multiplayer.js defines
    }
    
    private function attachPlayer($conn){
        $this->players[count($this->clients)] = $conn->resourceId;
    }
    
    private function getPlayerNumber($id){
        for($i=0; ;$i++){
            if($this->players[$i] == $id){
                return $i;
            }
        }
    }
}