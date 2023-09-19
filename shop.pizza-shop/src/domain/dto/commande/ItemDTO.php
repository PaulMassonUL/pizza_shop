<?php

namespace pizzashop\shop\domain\dto\commande;

class ItemDTO extends \pizzashop\shop\domain\dto\DTO
{

    public int $numero;
    public string $libelle;
    public int $taille;
    public string $libelle_taille;
    public float $tarif;
    public int $quantite;

    public function __construct(string $numero, int $taille, int $quantite)
    {
        $this->numero = $numero;
        $this->taille = $taille;
        $this->quantite = $quantite;
    }

    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function setLibelleTaille(string $libelle_taille): void
    {
        $this->libelle_taille = $libelle_taille;
    }

    public function setTarif(float $tarif): void
    {
        $this->tarif = $tarif;
    }
}