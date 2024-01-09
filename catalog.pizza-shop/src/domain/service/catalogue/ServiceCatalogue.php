<?php

namespace pizzashop\catalog\domain\service\catalogue;

use pizzashop\catalog\domain\dto\catalogue\ProduitDTO;
use pizzashop\catalog\domain\entities\catalogue\Produit;

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

    public function getProduits(): array
    {
        $produits = Produit::all();
        $produitsDTO = [];
        foreach ($produits as $produit) {
            $produitsDTO[] = new ProduitDTO(
                $produit->id,
                $produit->numero,
                $produit->libelle,
                $produit->description,
                $produit->tailles()->first()->pivot->tarif
            );
        }
        return $produitsDTO;
    }

}