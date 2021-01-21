require('dotenv').config({ path: __dirname + '/../.env' });
let fs = require('fs');

let certs = {
  key: fs.readFileSync('/etc/ssl/wa.customer/private.key'),
  cert: fs.readFileSync('/etc/ssl/wa.customer/certificate.crt')
};

const jwt = require('jsonwebtoken');
const express = require('express');
const app = express();
const http = require('https').Server(certs, app);
const bodyParser = require("body-parser");
const redis = require('redis');
const mongoose = require('mongoose');

require('./libs/db-connection');

const Chat = require('./models/Chat');

app.use(bodyParser.urlencoded({
    extended: true
}));
app.use(bodyParser.json());

app.post('/job-chat', (req, res) => {
    console.log(req.body.jobId);
    Chat.find({job_id:req.body.jobId}).sort('-date').skip(req.body.skip).limit(10).then(messages => {
        res.json(messages);
    }).catch(err => console.error(err));
});


// Socket Code //
const io = require('socket.io')(http);
const users = {};
const privateUsers = {};

io.on('connection', socket => {
           console.log('connected');
    // public chat
    socket.on('new-user', name => {
        console.log('userid connected:'+name);
        socket.nickname = name;
        if (typeof users[socket.nickname] == 'undefined') {
            users[socket.nickname] = name;
            socket.broadcast.emit('user-connected', name);
        }
    });

    socket.on('send-chat-message', message => {
         console.log({ username: socket.nickname, message: message });
         Chat.create({ username: message.username, message: message.message, job_id: message.job_id }).then((saveMessage) => {
            let emitChannel = 'chat-message'; //'chatmessage' + message.job_id
            socket.broadcast.emit(emitChannel, { message: message, name: message.username, job_id: message.job_id , message_id:saveMessage._id, check_string:message.check_string});
        }).catch(err => console.error(err));
    });

    socket.on('disconnect', () => {
        console.log('disconnet', users[socket.nickname]);
        if (typeof users[socket.nickname] != 'undefined') {
             console.log(users[socket.nickname]);
            // socket.broadcast.emit('user-disconnected', users[socket.id]);
            delete users[socket.nickname];
        }
    });
socket.on('send-driver-coordinates', details => {
        console.log({ details });
        socket.broadcast.emit('receive-driver-coordinates', { lat: details.lat, lng: details.lng, job_id: details.job_id });
    });

var langs = [{lat:26.660139815234928, lng: -80.2731823294655},
                {lat:26.660005580546965, lng: -80.27423375536107},
                {lat:26.660043933331064, lng: -80.27537101194197},
                {lat:26.65994805134664, lng: -80.27663701455091},
                {lat:26.65902758019731, lng: -80.27882569702739}];

                for (let i = 0; i <= 4; i++) {
                    setTimeout(function() {
        sendLocs(i);
        }, 30000);
//                if (i == 4){
//        i = 0;
//        }
        }

        function sendLocs(i) {
        
            console.log(langs[i]['lng']);
            var langArr = langs[i];
socket.broadcast.emit('receive-driver-coordinates', { lat: langArr['lat'], lng: langArr['lng'], job_id: 10 });
        
        }
});

// listen
http.listen(process.env.SOCKET_SERVER_PORT || 3000, () => {
    console.log('Listening at: http://' + process.env.SOCKET_SERVER_IP + ':' + process.env.SOCKET_SERVER_PORT);
});
