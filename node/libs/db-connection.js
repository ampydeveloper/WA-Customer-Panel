const mongoose = require('mongoose');
mongoose.Promise = global.Promise;

var mongoDB = 'mongodb://127.0.0.1/my_database';
mongoose.connect(mongoDB, { useNewUrlParser: true, useUnifiedTopology: true });

mongoose.connection
    .once('open', () => console.log('Connected to the database'))
    .on('error', err => console.error(err));