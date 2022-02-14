const express = require('express');

const movieController = require('../controllers');

const router = express.Router()

router.get('/movies', movieController.movie);

module.exports = router