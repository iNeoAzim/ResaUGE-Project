const mongoose = require('mongoose');

const UserSchema = new mongoose.Schema({
    // ...existing code...
    isAdmin: {
        type: Boolean,
        default: false,
    },
    // ...existing code...
});

module.exports = mongoose.model('User', UserSchema);
