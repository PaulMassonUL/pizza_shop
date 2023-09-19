<?php

namespace pizzashop\shop\domain\service\commande;

use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\entities\commande\Item;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class ServiceCommande implements iCommander
{
    private iInfoCatalogue $serviceCatalogue;
    private LoggerInterface $logger;

    public function __construct(iInfoCatalogue $serviceCatalogue, LoggerInterface $logger)
    {
        $this->serviceCatalogue = $serviceCatalogue;
        $this->logger = $logger;
    }

    public function accederCommande(string $id): CommandeDTO
    {
        try {
            $commande = Commande::findOrFail($id);
        } catch (\Exception) {
            throw new ServiceCommandeNotFoundException("Commande $id non trouvée");
        }
        return $commande->toDTO();
    }

    public function validerCommande(string $id): CommandeDTO
    {
        try {
            $commande = Commande::findOrFail($id);
        } catch (\Exception) {
            throw new ServiceCommandeNotFoundException("Commande $id non trouvée");
        }
        if ($commande->etat >= Commande::ETAT_VALIDE) {
            throw new ServiceCommandeInvalidTransitionException("Commande $id déjà validée");
        }
        $commande->update(['etat' => Commande::ETAT_VALIDE]);
        $this->logger->info("Commande $id validée");
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
        //creer les items
        foreach ($c->items as $itemDTO) {
            try {
                $infoItem = $this->serviceCatalogue->getProduit($itemDTO->numero, $itemDTO->taille);
            } catch (ServiceCatalogueNotFoundException) {
                throw new ServiceCommandeInvalidDataException("Produit non trouvé");
            }
            $item = new Item();
            $item->numero = $itemDTO->numero;
            $item->taille = $itemDTO->taille;
            $item->quantite = $itemDTO->quantite;

            $item->libelle = $infoItem->libelle;
            $item->libelle_taille = $infoItem->libelle_taille;
            $item->tarif = $infoItem->tarif;
            $commande->items()->save($item);
        }

        $commande->calculerMontantTotal();

        $this->logger->info("Commande $commande->id créée");
        return $commande->toDTO();
    }
}