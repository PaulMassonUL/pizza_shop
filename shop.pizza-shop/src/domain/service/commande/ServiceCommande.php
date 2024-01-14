<?php

namespace pizzashop\shop\domain\service\commande;

use Exception;
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

    /**
     * @throws ServiceCommandeNotFoundException
     */
    public function accederCommande(string $id): CommandeDTO
    {
        try {
            $commande = Commande::findOrFail($id);
        } catch (Exception) {
            throw new ServiceCommandeNotFoundException("Commande $id non trouvée");
        }
        return $commande->toDTO();
    }

    /**
     * @throws ServiceCommandeNotFoundException
     * @throws ServiceCommandeInvalidTransitionException
     */
    public function validerCommande(string $id): CommandeDTO
    {
        try {
            $commande = Commande::findOrFail($id);
        } catch (Exception) {
            throw new ServiceCommandeNotFoundException("Commande $id non trouvée");
        }
        if ($commande->etat >= Commande::ETAT_VALIDE) {
            $etat = match ($commande->etat) {
                Commande::ETAT_VALIDE => Commande::ETAT_VALIDE_LIBELLE,
                Commande::ETAT_PAYEE => Commande::ETAT_PAYEE_LIBELLE,
                default => '',
            };
            throw new ServiceCommandeInvalidTransitionException($etat);
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
            'delai' => 0,
            'etat' => Commande::ETAT_CREE
        ]);

        // Collectez tous les numéros et tailles d'item distincts de la commande
        $numerostaillesItems = array_map(function ($itemDTO) {
            return $itemDTO->numero . '-' . $itemDTO->taille;
        }, $c->items);
        $numerostaillesItems = array_unique($numerostaillesItems);

        // Utilisez ces numéros et tailles pour effectuer une seule requête vers le catalogue
        try {
            $infosItems = $this->serviceCatalogue->getProduits($numerostaillesItems);

            // Créez les items de la commande en utilisant les informations obtenues
            foreach ($c->items as $itemDTO) {
                $key = $itemDTO->numero . '-' . $itemDTO->taille;
                $infoItem = $infosItems[$key];

                $item = new Item();
                $item->numero = $itemDTO->numero;
                $item->taille = $itemDTO->taille;
                $item->quantite = $itemDTO->quantite;
                $item->libelle = $infoItem->libelle_produit;
                $item->libelle_taille = $infoItem->libelle_taille;
                $item->tarif = $infoItem->tarif;

                $commande->items()->save($item);
            }
        } catch (ServiceCatalogueNotFoundException) {
            throw new ServiceCommandeInvalidDataException("Produit non trouvé");
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
            v::attribute('mail_client', v::email())
                ->attribute('type_livraison', v::in([Commande::TYPE_LIVRAISON_SUR_PLACE, Commande::TYPE_LIVRAISON_DOMICILE, Commande::TYPE_LIVRAISON_A_EMPORTER]))
                ->attribute('items', v::arrayVal()->notEmpty()
                    ->each(v::attribute('numero', v::intVal()->positive())
                        ->attribute('taille', v::in([1, 2]))
                        ->attribute('quantite', v::intVal()->positive())
                    ))
                ->assert($c);

        } catch (NestedValidationException $e) {
            throw new ServiceCommandeInvalidDataException("Données de commande invalides");
        }
    }


}