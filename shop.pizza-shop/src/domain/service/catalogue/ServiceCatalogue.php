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

    public function getProduits(array $numerostailles): array
    {
        $produitsDTO = [];

        try {
            $produits = Produit::whereIn('numero', array_column($numerostailles, 'numero'))
                ->with('tailles') // Charger la relation tailles
                ->get();

            foreach ($numerostailles as $numerotaille) {
                $produit = $produits->firstWhere('numero', $numerotaille['numero']);

                if ($produit) {
                    $taille = $produit->tailles->where('id', $numerotaille['taille'])->first();

                    if ($taille) {
                        $produitsDTO[$numerotaille['numero'] . '-' . $numerotaille['taille']] = new ProduitDTO(
                            $produit->numero,
                            $produit->libelle,
                            $taille->libelle,
                            $taille->pivot->tarif
                        );
                    }
                }
            }
        } catch (\Exception) {
            throw new ServiceCatalogueNotFoundException("Erreur lors de la récupération des produits");
        }

        return $produitsDTO;
    }

}