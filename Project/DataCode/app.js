const express = require('express');
const app = express();
// ...existing code...
const adminRoutes = require('./routes/admin');
// ...existing code...

app.use('/admin', adminRoutes);

// ...existing code...
module.exports = app;
