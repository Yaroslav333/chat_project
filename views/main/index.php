


<div class="row">
    <div class="col-sm-12 col-md-8 col-lg-8">
        <?php
        if(Yii::$app->user->enableSession):

            echo 'Вы зашли как: ' . Yii::$app->user->identity->username;
        endif;
        ?>

            <h1>Тело чата</h1>

         форма для отправки сообщений
        <form  name="publish" onsubmit="msg(this.message.value);return false;">
            <input  type="text" name="message" oninput="validateComments(this)" >
            <input id="q" type="submit" value="Отправить" onclick="">
            <button id="stop" onclick="buttonStop">Покинуть чат</button>

            <div id="status"></div>

        </form>
        <!-- здесь будут появляться входящие сообщения -->
        <div id="subscribe"></div>


</div>

    <script>

         //создать подключение
        var socket = new WebSocket("ws://project:8080//?token=<?= Yii::$app->user->identity->auth_key ?>");
         socket.onopen = function(event) {
             console.log('Connection established');
             // Display user friendly messages for the successful establishment of connection
         }

        // отправить сообщение из формы publish
         function msg(mes) {
            var data = {
                type:'message',
                text:mes,
            };
            socket.send(JSON.stringify(data));

             document.getElementById("q").disabled = true;

             function sleep(ms) {
                 return new Promise(resolve => setTimeout(resolve, ms));
             }

             async function demo() {
                 console.log('Taking a break...');
                 await sleep(15000);
                 console.log('15 second later');
                 document.getElementById("q").disabled = false;

             }
             demo();



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

                case 'ban':

                    var label = document.getElementById('status');
                    label.innerHTML = 'Вас забанили';

                    break;


                case 'mute':

                    document.getElementById("q").disabled = true;
            }

//           var message = jsonObject.text;
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

//                 // создание "виртуального образа" кнопки
//                 var btn = document.createElement (('v' == '\v') ? '<input name="myName">' : 'input'); btn.name = 'myName';
//                 btn.type = 'button'; // или 'submit', или 'reset';
//                 btn.id = 'myUniqueID';
//                 btn.value = k.cid; // или 'Отправить', или 'Очистить'...
//                 btn.style.cssText = 'color: red; margin-top: 30px; ...';
//                 btn.name ='ban';
//               // btn.onclick = banUser(k.name, k.cid);
//
//                 // "приживление" кнопки
//
//                 document.getElementById (k.cid).appendChild (btn);

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


         function banUser (name, id) {

             var data = {
                 type:'ban',
                 text:name,
                 id: id
             };

             socket.send(JSON.stringify(data));
             return false;

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


