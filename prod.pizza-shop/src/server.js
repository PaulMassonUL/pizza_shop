import express from 'express';
import route from '../config/routes.js';
import ServiceConsumeMsg from '../src/domain/service/ServiceConsumeMsg.js';

const app = express();
const port = process.env.PORT || 3000;

route(app);

const startApplication = async () => {
    try {
        const serviceConsumeMsg = new ServiceConsumeMsg();
        await serviceConsumeMsg.startListening();

        app.listen(port, () =>
            console.log(`App listening on port ${port}!`)
        );
    } catch (error) {
        console.error(`Error starting application: ${error.message}`);
    }
};

startApplication().then(r => console.log('Application started'));
