import amqp from 'amqplib';
import ServiceCommande from './ServiceCommande.js';

class ServiceConsumeMsg {

    constructor() {
        this.serviceCommande = new ServiceCommande();
    }

    async startListening() {
        const rabbitmq = 'amqp://user:password@rabbitmq';
        const queue = 'nouvelles_commandes';

        const conn = await amqp.connect(rabbitmq);
        const channel = await conn.createChannel();

        await channel.consume(queue, async (msg) => {
            const commande = await this.createCommandeFromMessage(msg);
            channel.ack(msg);
        });
    }

    async createCommandeFromMessage(msg) {
        const messageContent = JSON.parse(msg.content.toString());
        // Ajoutez une logique pour extraire des données du message si nécessaire
        return await this.serviceCommande.createCommande(messageContent);
    }

}

export default ServiceConsumeMsg;