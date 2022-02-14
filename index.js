const router = require("./routes");

const express = require('express');

const app = express();

const port = 8080;

app.use('/', router);

app.listen(port, () => {
    console.log(`listening on ${port}`)
});

