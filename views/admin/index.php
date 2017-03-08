<h1>Админ панель</h1>



<div class="container">
    <h2>Список пользователей</h2>

    <table class="table table-bordered">

        <tr>
            <th>Name</th>
            <th>Status</th>

            <?php foreach ($model as $user):?>
        <tr>

            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['status']; ?></td>

            <td><button  onclick="banUser('<?=$user['username']?>')">Ban</button><button onclick="unBanUser('<?=$user['username']?>')">Разбанить</button></td>
            <td><button onclick="muteUser('<?=$user['id']?>')">Mute</button></td>

        </tr>
        <?php endforeach; ?>


        </tr>

    </table>


<div class="row">
    <div class="col-sm-12 col-md-8 col-lg-8">
        <?php
        if(Yii::$app->user->enableSession):

            echo 'Вы зашли как: ' . Yii::$app->user->identity->username;
        endif;
        ?>

        <h1>Тело чата</h1>

        форма для отправки сообщений
        <form  name="publish">
            <input type="text" name="message" oninput="validateComments(this)">
            <input type="submit" value="Отправить">
            <button id="stop" onclick="buttonStop">Покинуть чат</button>


            <div id="status"></div>

        </form>

        <!-- здесь будут появляться входящие сообщения -->
        <div id="subscribe"></div>
        <div id="test"></div>


    </div>

    <script>

        //создать подключение
        var socket = new WebSocket("ws://project:8080//?token=<?= Yii::$app->user->identity->auth_key ?>");

        socket.onopen = function(event) {
            console.log('Connection established');
            // Display user friendly messages for the successful establishment of connection

        }
        // отправить сообщение из формы publish
        document.forms.publish.onsubmit = function() {
            // var outgoingMessage = this.message.value;

            var data = {
                type:'message',
                text:this.message.value,
            };

            socket.send(JSON.stringify(data));

            return false;
        };


        // обработчик входящих сообщений
        socket.onmessage = function(event) {

            var jsonObject = JSON.parse(event.data);

            var type = jsonObject.type;

            switch (type) {
                case 'userlist':
                    // новый список пользователей
                    /*
                     что тут должно происходить:
                     - очистить контейнер с списком пользователей
                     - перебрать полученный массив и сгенерировать список
                     */
                    showUser(jsonObject.list);

                    break;

                case 'message':
                    showMessage(jsonObject.text);
                    break;


                case 'close':
                    $.each(jsonObject.list, function (i,k) {
                        var el = document.getElementById(k);
                        el.parentNode.removeChild(el);
                    });
                    break;
            }


//            var message = jsonObject.text;
//            var incomingMessage = event.data;
//            showMessage(message);

        };

        function showUser(userList) {
            var label = document.getElementById('user');
            label.innerHTML = '';
            $.each(userList,function(i,k){
                var userElem = document.createElement('li');
                userElem.appendChild(document.createTextNode(k.cid+': '+k.name));
                document.getElementById('user').appendChild(userElem);
                userElem.id = k.cid;

                $('li').each(function(){
                    get_random_color();
                    $(this).css('color',randomcolor);
                });

            });
        }


        // показать сообщение в div#subscribe
        function showMessage(message) {
            var messageElem = document.createElement('p');
            messageElem.appendChild(document.createTextNode(message));
            document.getElementById('subscribe').appendChild(messageElem);

            $('p').each(function(){
                get_random_color(); //запускаем функцию
                $(this).css('color',randomcolor); //для каждого p добавляем атрибут style="color:#код;"


            });


        }

        var buttonStop = document.getElementById('stop');

        //Handling the click event
        buttonStop.onclick = function ( ) {
            // Close the connection if open
            if (socket.readyState === WebSocket.OPEN){
                socket.close();

                var label = document.getElementById('status');
                label.innerHTML = 'соединение закрыто';
            }
        };


        function banUser (name) {
            var data = {
                type:'ban',
                text:name,

            };
            socket.send(JSON.stringify(data));


        }

        function unBanUser (name) {
            var data = {
                type:'unban',
                text:name,

            };
            socket.send(JSON.stringify(data));

        }
        function muteUser(id) {

            var data = {
                type:'mute',
                id:id,
            };
            socket.send(JSON.stringify(data));

        }


        //         var btnChat = document.getElementById('chat');
        //
        //         //Handling the click event
        //         buttonStop.onclick = function ( ) {
        //
        //             if (socket.readyState !== WebSocket.OPEN){
        //                 socket.onopen( );
        //
        //             }
        //
        //             var label = document.getElementById('status');
        //             label.innerHTML = label.innerHTML = 'соединение установлено для id: ' + '<?php //echo Yii::$app->user->id;  ?>//';
        //
        //         }

        function validateComments(input) {
            if (input.value.length > 200) {
                input.setCustomValidity("Длина сообщения не более 200 символов.");
            }
            else {
                // Длина комментария отвечает требованию,
                // поэтому очищаем сообщение об ошибке
                input.setCustomValidity("");
            }
        }




        //  --------------------------------------------

    </script>


    <div class="col-sm-12 col-md-4 col-lg-4">
        <h1>Список активных пользователей</h1>

        <ul id="user"></ul>

    </div>
</div>

    <script type="text/javascript">
        var randomcolor;
        function get_random_color() {
            randomcolor="#"+((1<<24)*Math.random()|0).toString(16);
        }

    </script>