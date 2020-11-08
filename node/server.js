require('dotenv').config({ path: __dirname + '/../.env' });

const jwt = require('jsonwebtoken');
const express = require('express');
const app = express();
const http = require('http').Server(app);
const bodyParser = require("body-parser");
const redis = require('redis');
const mongoose = require('mongoose');
const privateChatSchema = require('./models/PrivateChat');

require('./libs/db-connection');

const Chat = require('./models/Chat');

app.use(bodyParser.urlencoded({
    extended: true
}));

app.use(bodyParser.json());

app.post('/job-chat', (req, res) => {
    Chat.find({job_id:req.body.jobId}).sort('-date').limit(10).then(messages => {
        res.json(messages);
    }).catch(err => console.error(err));
});

//app.post('/private-chat', (req, res) => {
//
//    let table = 'chat-' + req.body.uniqueid;
//    console.log(table);
//    var PChat = mongoose.model(table, privateChatSchema);
//    PChat.find({}).sort('-date').limit(10).then(messages => {
//        // console.log(messages);
//        res.json(messages);
//    }).catch(err => console.error(err));
//});



// Socket Code //
const io = require('socket.io')(http);
const users = {};
const privateUsers = {};


///// Redis /////
console.log(process.env.REDIS_PORT, process.env.REDIS_HOST);
// const redisClient = redis.createClient(process.env.REDIS_PORT, process.env.REDIS_HOST);
const redisClient = redis.createClient();
redisClient.subscribe('live-new-model');
redisClient.subscribe('broadcast-message');
redisClient.subscribe('message');
redisClient.on('live-new-model', function(channel, message) {
    io.emit('live-new-model', "111111fdbfgnfghmnfgb");
});

redisClient.on('message', function(channel, message) {

    io.emit(channel, message);
});



io.on('connection', socket => {
           console.log('connected');
    // public chat
    socket.on('new-user', name => {
        console.log(name);
        socket.nickname = name;
        if (typeof users[socket.nickname] == 'undefined') {
            users[socket.nickname] = name;
            socket.broadcast.emit('user-connected', name);
        }
    });

    socket.on('send-chat-message', message => {
         console.log({ username: socket.nickname, message: message });
        Chat.create({ username: socket.nickname, message: message.message, job_id: message.job_id }).then(() => {
            socket.broadcast.emit('chat-message', { message: message, name: users[socket.nickname], job_id: message.job_id });
        }).catch(err => console.error(err));
    });

    socket.on('disconnect', () => {
        console.log('disconnet', privateUsers[socket.nickname]);
        if (typeof users[socket.nickname] != 'undefined') {
             console.log(users[socket.nickname]);
            // socket.broadcast.emit('user-disconnected', users[socket.id]);
            delete users[socket.nickname];
        }

//        if (typeof privateUsers[socket.nickname] != 'undefined') {
//            let emit = 'private-user-disconnected-' + privateUsers[socket.nickname].token;
//            socket.broadcast.emit(emit, privateUsers[socket.nickname].name);
//            delete privateUsers[socket.nickname];
//        }
    });


    // one to one chat
//    socket.on('private-new-user', data => {
//        socket.nickname = data.name;
//        if (typeof privateUsers[socket.nickname] == 'undefined') {
//            privateUsers[socket.nickname] = data;
//            let emit = 'private-user-connected-' + data.token;
//            socket.broadcast.emit(emit, data.name);
//        }
//    });
//
//    socket.on('private-send-chat-message', message => {
//        let table = 'chat-' + privateUsers[socket.nickname].getUniqueID;
//        var PChat = mongoose.model(table, privateChatSchema);
//        PChat.create({ username: privateUsers[socket.nickname].name, message: message }).then(() => {
//            let emit = 'private-chat-message-' + privateUsers[socket.nickname].token;
//            socket.broadcast.emit(emit, { message: message, name: privateUsers[socket.nickname].name });
//        }).catch(err => console.error(err));
//
//    });


});


// listen
http.listen(process.env.SOCKET_SERVER_PORT || 3000, () => {
    console.log('Listening at: http://' + process.env.SOCKET_SERVER_IP + ':' + process.env.SOCKET_SERVER_PORT);
});