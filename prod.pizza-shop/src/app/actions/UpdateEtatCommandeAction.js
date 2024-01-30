import ServiceCommande from "../../domain/service/ServiceCommande.js";
class UpdateEtatCommandeAction {

    static async updateCommandeEtat(req, res) {
        const commandeId = req.params.id;
        const nouvelEtat = req.body.etape;
        try {
            await ServiceCommande.updateCommandeEtat(commandeId, nouvelEtat);
            res.status(200).json({
                message: `L'état de la commande ${commandeId} a été mis à jour avec succès`,
                etat: nouvelEtat
            });
        } catch (error) {
            res.status(500).send(error.message);
        }
    }

}

export default UpdateEtatCommandeAction;

