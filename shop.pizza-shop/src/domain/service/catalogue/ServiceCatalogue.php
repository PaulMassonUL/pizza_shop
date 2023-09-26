<?php

namespace pizzashop\shop\domain\service\catalogue;

use pizzashop\shop\domain\dto\catalogue\ProduitDTO;
use pizzashop\shop\domain\entities\catalogue\Produit;

class ServiceCatalogue implements iInfoCatalogue
{

    public function getProduit(int $numero, int $taille): ProduitDTO
    {
        try {
            $produit = Produit::where('numero', $numero)->firstOrFail();

            $taille = $produit->tailles()->where('taille_id', $taille)->firstOrFail();

            $produitDTO = new ProduitDTO(
                $produit->numero,
                $produit->libelle,
                $taille->libelle,
                $taille->pivot->tarif
            );
        } catch (\Exception) {
            throw new ServiceCatalogueNotFoundException("Produit $numero non trouvée");
        }
        return $produitDTO;
    }

}