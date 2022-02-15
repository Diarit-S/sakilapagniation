const Movie = require('../models/movies')
const movieModel = new Movie();

exports.movie = async (req, res) => {
    const params = {orderByType: req.query.sort, orderBy: req.query.order, limit: Math.floor(parseInt(req.query.limit)), page: Math.floor(parseInt(req.query.page))}
    const movies = await movieModel.getMovies(params)

    res.json(movies);
}


