const mongoose = require('mongoose');
const chatSchema = new mongoose.Schema({
    username: String,
    message: String,
    date: { type: Date, default: Date.now }
});

module.exports = chatSchema;