import express from 'express';
import helmet from 'helmet';
import CommandesAction from '../src/app/actions/CommandesAction.js';
import UpdateEtatCommandeAction from "../src/app/actions/UpdateEtatCommandeAction.js";

export default function (app) {
    app.use(helmet());
    app.use(express.json());
    app.use(express.urlencoded({ extended: false }));

    app.get('/commandes', CommandesAction.getCommandes);

    app.patch('/commande/:id/etape', UpdateEtatCommandeAction.updateCommandeEtat);

}