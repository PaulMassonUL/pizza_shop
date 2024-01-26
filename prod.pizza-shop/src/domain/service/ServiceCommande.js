import mysqlKnex from "../../../config/knex.js";

async function getCommandes() {
    return mysqlKnex({c: "commande"})
        .select({
            id: 'c.id',
            date: 'c.date_commande',
            delai: 'c.delai',
            type_livraison: 'c.type_livraison',
            etape: 'c.etape',
            montant_total: 'c.montant_total',
            mail_client: 'c.mail_client',
        })
        .orderBy('c.date_commande', 'desc');
}

export { getCommandes };




