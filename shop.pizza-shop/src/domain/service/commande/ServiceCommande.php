<?php

namespace pizzashop\shop\domain\service\commande;

use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use Ramsey\Uuid\Uuid;

class ServiceCommande implements iCommander
{

    public function accederCommande(string $id): CommandeDTO
    {
        try {
            $commande = Commande::findOrFail($id);
        } catch (\Exception) {
            throw new ServiceCommandeNotFoundException("Commande non trouvée");
        }
        return $commande->toDTO();
    }

    public function validerCommande(string $id): CommandeDTO
    {
        try {
            $commande = Commande::findOrFail($id);
        } catch (\Exception) {
            throw new ServiceCommandeNotFoundException("Commande non trouvée");
        }
        if ($commande->etat >= Commande::ETAT_VALIDE) {
            throw new ServiceCommandeInvalidTransitionException("Commande déjà validée");
        }
        $commande->update(['etat' => Commande::ETAT_VALIDE]);
        //mettre le logger ici $this->logger->info("Commande $idCommande validée");
        return $commande->toDTO();
    }

    public function creerCommande(CommandeDTO $c): CommandeDTO
    {
//        $this->validerDonneesDeCommande($c);

        $uuid = Uuid::uuid4();
        $commande = Commande::create([
            'id' => $uuid->toString(),
            'date' => date('Y-m-d H:i:s'),
            'type_livraison' => $c->type_livraison,
            'mail_client' => $c->mail_client,
            'montant' => $c->montant,
            'delai' => 0,
            'etat' => Commande::ETAT_CREE
        ]);
        //creer items

        return $commande->toDTO();
    }
}