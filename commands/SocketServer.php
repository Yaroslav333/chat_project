<?php


namespace app\commands;
use app\models\User;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use yii\helpers\ArrayHelper;



class SocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $users;


    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later

//     print_r($conn->WebSocket->request->getQuery()->get('token'));
//
//        exit;
        $this->clients->attach($conn);
        // запоминаем пользователя в списке всех пользователей, подключенных к серверу
        // пользователя ищем по токену
      $user = User::findOne(['auth_key'=>$conn->WebSocket->request->getQuery()->get('token')]);
        //$user = User::findOne(['auth_key'=>$conn->WebSocket->request->get('auth_key')]);

        if (!$user){
            // пользователь не найден по токену, отключаем клиента
            echo 'user not found';
            $conn->close();
            return;
        }
         //   $this->users[$user->id] = $user;
       $this->users[$conn->resourceId] = $user;
        // рассылаем всем текущим пользователям новый список он-лайн
        $data = [];
        foreach ($this->users as $cid=>$user){
            $data[]=['cid'=>$cid,'name'=>$user->username];
        }

        foreach ($this->clients as $client) {
                $client->send(json_encode(['type' => 'userlist', 'list' => $data]));
        }

        // сказать всем, что пользователь зашел в чат

        echo "New connection! ({$conn->resourceId})\n";
        echo 'количество пользователей ' . $this->clients->count();


    }

    public function onMessage(ConnectionInterface $from, $msg) {

        $data = json_decode($msg);
        if (!$data || !isset($data->type)){
            return;
        }
        switch ($data->type){
            case 'message':

                $numRecv = count($this->clients) - 1;
                echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
                    , $from->resourceId, $data->text, $numRecv, $numRecv == 1 ? '' : 's');

                foreach ($this->clients as $client) {
                    // if ($from !== $client) {
                    // The sender is not the receiver, send to each client connected
                    $client->send($msg);
                }

                break;
            case 'ban':
               // echo $data->text;


                $user = User::findOne(['username'=>$data->text]);
                $user->status = User::STATUS_DELETED;
                $user->save();

                foreach ($this->users as $rid => $user) {
                    if ($user->username == $data->text){
                        foreach ($this->clients as $client){
                            if ($client->resourceId == $rid){
                                //$client->send(json_encode(['type'=>'ban']));
                                $client->close();
                                break;
                            }
                        }
                    }

                }

                break;

            case 'unban':
                $user = User::findOne(['username'=>$data->text]);
                $user->status = User::STATUS_ACTIVE;
                $user->save();


                break;
            case 'mute':

                foreach ($this->users as $rid => $user) {
                    if ($user->id == $data->id){
                        foreach ($this->clients as $client){
                            if ($client->resourceId == $rid){
                                $client->send(json_encode(['type'=>'mute']));
                               // $client->close();
                                break;
                            }
                        }
                    }

                }

                break;
        }

//        $numRecv = count($this->clients) - 1;
//        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
//            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');



      // }
    }

    public function onClose(ConnectionInterface $conn) {
        // сказать всем, что пользователь вышел из часа
        // The connection is closed, remove it, as we can no longer send it messages

        foreach ($this->clients as $client) {

            $data = ['id' => $conn->resourceId];

            $client->send(json_encode(['type' => 'close', 'list' => $data]));

            unset($this->users[$conn->resourceId]);

           // $user = User::findOne(['auth_key'=>$conn->WebSocket->request->getQuery()->get('token')]);



        }


        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";

        echo 'количество пользователей ' . $this->clients->count();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}