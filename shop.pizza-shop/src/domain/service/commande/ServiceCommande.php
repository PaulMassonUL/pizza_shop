<?php

namespace pizzashop\shop\domain\service\commande;

use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\entities\commande\Item;
use pizzashop\shop\domain\service\catalogue\iInfoCatalogue;
use pizzashop\shop\domain\service\catalogue\ServiceCatalogueNotFoundException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

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

    /**
     * @throws ServiceCommandeInvalidDataException
     */
    public function creerCommande(CommandeDTO $c): CommandeDTO
    {
        $this->validerDonneesDeCommande($c);

        $uuid = Uuid::uuid4();
        $commande = Commande::create([
            'id' => $uuid->toString(),
            'date_commande' => date('Y-m-d H:i:s'),
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

            $item->libelle = $infoItem->libelle_produit;
            $item->libelle_taille = $infoItem->libelle_taille;
            $item->tarif = $infoItem->tarif;
            $commande->items()->save($item);
        }

        $commande->calculerMontantTotal();

        $this->logger->info("Commande $commande->id créée");
        return $commande->toDTO();
    }


    /**
     * @throws ServiceCommandeInvalidDataException
     */
    public function validerDonneesDeCommande(CommandeDTO $c): void
    {
        try {
            v::email()->assert($c->mail_client);
            v::in([Commande::TYPE_LIVRAISON_SUR_PLACE, Commande::TYPE_LIVRAISON_DOMICILE, Commande::TYPE_LIVRAISON_A_EMPORTER])->assert($c->type_livraison);
            v::arrayType()->notEmpty()->assert($c->items);
            foreach ($c->items as $item) {
                v::intVal()->positive()->assert($item->numero);
                v::intVal()->positive()->assert($item->quantite);
                v::in([Item::TAILLE_NORMALE, Item::TAILLE_GRANDE])->assert($item->taille);
            }
        } catch (NestedValidationException) {
            throw new ServiceCommandeInvalidDataException("Données de commande invalides");
        }
    }



}