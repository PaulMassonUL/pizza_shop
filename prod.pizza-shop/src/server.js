import express from 'express';
import route from '../config/routes.js';

const app = express();
const port = process.env.PORT || 3000;

route(app);
app.listen(port, () =>
    console.log(`app listening on port ${port}!`
    )
);
