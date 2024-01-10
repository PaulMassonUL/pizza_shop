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
            $produitDTO = new ProduitDTO(
                $produit->id,
                $produit->numero,
                $produit->libelle,
                $produit->description
            );
            $produitDTO->tarif_normale = $produit->tailles()->where('taille_id', 1)->firstOrFail()->pivot->tarif;
            $produitDTO->tarif_grande = $produit->tailles()->where('taille_id', 2)->firstOrFail()->pivot->tarif;
            $produitDTO->image = $produit->image;
            $produitDTO->categorie = $produit->categorie->libelle;
        } catch (\Exception) {
            throw new ServiceCatalogueNotFoundException("Produit $id non trouvée");
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
                $produit->description
            );
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