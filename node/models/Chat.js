const mongoose = require('mongoose');

const chatSchema = new mongoose.Schema({
    username: String,
    message: String,
    job_id: String,
    date: { type: Date, default: Date.now }
});

module.exports = mongoose.model('waJobChat', chatSchema);