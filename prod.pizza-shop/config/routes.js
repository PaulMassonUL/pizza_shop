import express from 'express';
import helmet from 'helmet';
import {getCommandes} from '../src/domain/service/ServiceCommande.js';

export default function (app) {
    app.use(helmet());
    app.use(express.json());
    app.use(express.urlencoded({ extended: false }));

    app.get('/', (req, res) =>
        res.send('Hello World!'));

    app.get('/commandes', async (req, res) => {
        const commandes = await getCommandes();
        res.json(commandes);
    });


}