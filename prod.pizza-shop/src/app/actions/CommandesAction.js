import ServiceCommande from "../../domain/service/ServiceCommande.js";

class CommandesAction {
    static async getCommandes(req, res) {
        try {
            const commandes = await ServiceCommande.getCommandes();
            res.json(commandes);
        } catch (error) {
            console.error(error);
            res.status(500).json({ error: 'Internal Server Error' });
        }
    }
}

export default CommandesAction;