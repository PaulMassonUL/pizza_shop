<?php

namespace pizzashop\catalog\domain\dto\catalogue;

class ProduitDTO extends \pizzashop\catalog\domain\dto\DTO
{
    public int $id;
    public int $numero;
    public string $libelle_produit;
    public string $libelle_taille;
    public float $tarif;

    public function __construct(int $id, int $numero, string $libelle_produit, string $libelle_taille, float $tarif)
    {
        $this->id = $id;
        $this->numero = $numero;
        $this->libelle_produit = $libelle_produit;
        $this->libelle_taille = $libelle_taille;
        $this->tarif = $tarif;
    }


}