<?php

namespace pizzashop\catalog\domain\dto\catalogue;

class ProduitDTO extends \pizzashop\catalog\domain\dto\DTO
{
    public int $id;
    public int $numero;
    public string $libelle_produit;
    public string $description;
    public array $tailles;

    public string $image;

    public array $categorie;

    public function __construct(int $id, int $numero, string $libelle_produit, string $description)
    {
        $this->id = $id;
        $this->numero = $numero;
        $this->libelle_produit = $libelle_produit;
        $this->description = $description;
    }


}