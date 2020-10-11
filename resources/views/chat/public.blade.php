<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat App</title>
    <script src='http://{{env("SOCKET_SERVER_IP").":".env("SOCKET_SERVER_PORT")}}/socket.io/socket.io.js'></script>
  <style>
    body {
      padding: 0;
      margin: 0;
      display: flex;
      justify-content: center;
    }

    #message-container {
      width: 80%;
      max-width: 1200px;
    }

    #message-container div {
      background-color: #CCC;
      padding: 5px;
    }

    #message-container div:nth-child(2n) {
      background-color: #FFF;
    }

    #send-container {
      position: fixed;
      padding-bottom: 30px;
      bottom: 0;
      background-color: white;
      max-width: 1200px;
      width: 80%;
      display: flex;
    }

    #message-input {
      flex-grow: 1;
    }
  </style>
</head>
<body>
    <?php
    if(Auth::user()->username != null){
        $username = Auth::user()->username;
    }else{
        $username = Auth::user()->email;
    }
    ?>
  <div id="message-container">
    @foreach($messages as $msg)
      <div>{{ (isset($msg->username)) ? (($msg->username != auth()->user()->username) ? $msg->username : "You")  :'Unknown'}}: {{$msg->message}}</div>
    @endforeach
  </div>
  <form id="send-container">
    <input type="text" id="message-input">
    <button type="submit" id="send-button">Send</button>
  </form>

  <script>
        var socket = io.connect('http://{{env("SOCKET_SERVER_IP").":".env("SOCKET_SERVER_PORT")}}', {secure: true});
        const messageContainer = document.getElementById('message-container')
        const messageForm = document.getElementById('send-container')
        const messageInput = document.getElementById('message-input')

        // const name = prompt('What is your name?');
        const name = "{{$username}}";
        // const name = "hhh";
        appendMessage('You joined')
        socket.emit('new-user', name)

        socket.on('chat-message', data => {
            if(data.name == name) {
                appendMessage(`You: ${data.message}`);
            } else {
                appendMessage(`${data.name}: ${data.message}`);
            }
        });

        socket.on('user-connected', name => {
        appendMessage(`${name} connected`)
        })

        // socket.on('user-disconnected', name => {
        // appendMessage(`${name} disconnected`)
        // })

        messageForm.addEventListener('submit', e => {
            e.preventDefault()
            const message = messageInput.value
            appendMessage(`You: ${message}`)
            socket.emit('send-chat-message', message)
            messageInput.value = ''
        })

        function appendMessage(message) {
            const messageElement = document.createElement('div')
            messageElement.innerText = message
            messageContainer.append(messageElement)
        }
    </script>
</body>
</html>