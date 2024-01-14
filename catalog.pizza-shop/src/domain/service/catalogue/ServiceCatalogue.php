<?php

namespace pizzashop\catalog\domain\service\catalogue;

use pizzashop\catalog\domain\dto\catalogue\ProduitDTO;
use pizzashop\catalog\domain\entities\catalogue\Produit;

class ServiceCatalogue implements iInfoCatalogue
{

    public function getProduitById(int $id): ProduitDTO
    {
        try {
            $produit = Produit::findOrFail($id);
        } catch (\Exception) {
            throw new ServiceCatalogueNotFoundException("Produit $id non trouvée");
        }
        return $produit->toDTO();
    }

    public function getProduits(): array
    {
        $produits = Produit::all();
        $produitsDTO = [];
        foreach ($produits as $produit) {
            $produitsDTO[] = $produit->toDTO();
        }
        return $produitsDTO;
    }

    public function getProduitsCommande(array $numerostailles): array
    {
        $produitsDTO = [];

        try {
            $produits = Produit::whereIn('numero', array_column($numerostailles, 'numero'))
                ->with('tailles') // Charger la relation tailles
                ->get();
            var_dump($numerostailles);
            foreach ($numerostailles as $numerotaille) {
                $produit = $produits->firstWhere('numero', $numerotaille['numero']);

                if ($produit) {
                    $taille = $produit->tailles->where('id', $numerotaille['taille'])->first();

                    if ($taille) {
                        $produitsDTO[$produit->numero . '-' . $taille->id] = [
                            'id' => $produit->id,
                            'numero' => $produit->numero,
                            'libelle_produit' => $produit->libelle,
                            'description' => $produit->description,
                            'image' => $produit->image,
                            'categorie' => $produit->categorie->toArray(),
                            'taille' => [
                                'libelle' => $taille->libelle,
                                'tarif' => $taille->pivot->tarif
                            ]
                        ];
                    }
                }
            }
        } catch (\Exception) {
            throw new ServiceCatalogueNotFoundException("Erreur lors de la récupération des produits");
        }

        return $produitsDTO;
    }

    public function getProduitsCategorie(int $id_categorie): array
    {
        $produits = Produit::where('categorie_id', $id_categorie)->get();
        $produitsDTO = [];
        foreach ($produits as $produit) {
            $produitsDTO[] = new ProduitDTO(
                $produit->id,
                $produit->numero,
                $produit->libelle,
                $produit->description
            );
        }
        return $produitsDTO;
    }

}