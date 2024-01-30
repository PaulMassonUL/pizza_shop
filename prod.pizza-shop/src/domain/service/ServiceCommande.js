import mysqlKnex from "../../../config/knex.js";

class ServiceCommande {
    static async getCommandes() {
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

    static async updateCommandeEtat(commandeId, nouvelEtat) {
        if (nouvelEtat < 1 || nouvelEtat > 3) {
            throw new Error(`La valeur de nouvelEtat doit être comprise entre 1 et 3.`);
        }
        try {
            await mysqlKnex({c: "commande"})
                .where({id: commandeId})
                .update({etape: nouvelEtat});
        } catch (error) {
            throw new Error(`Erreur lors de la mise à jour de l'état de la commande : ${error.message}`);
        }
    }

     async createCommande(commandeInfo) {
        try {
            const [newCommandeId] = await mysqlKnex('commande').insert({
                delai: commandeInfo.delai,
                id: commandeInfo.id,
                date_commande: commandeInfo.date_commande,
                type_livraison: commandeInfo.type_livraison,
                etape: 1, // État initial : RECUE
                montant_total: commandeInfo.montant_total,
                mail_client: commandeInfo.mail_client,
            });

            return newCommandeId;

        } catch (error) {
            throw new Error(`Erreur lors de la création de la commande : ${error.message}`);
        }
    }

}

export default ServiceCommande;






