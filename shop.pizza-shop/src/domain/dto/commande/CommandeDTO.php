<?php

namespace pizzashop\shop\domain\dto\commande;

class CommandeDTO extends \pizzashop\shop\domain\dto\DTO
{

    public string $id;
    public string $date;
    public int $type_livraison;
    public string $mail_client;
    public float $montant_total;
    public int $delai;

    public int $etat;

    public array $items;

    public function __construct(string $id, string $mail_client, int $type_livraison, string $date, float $montant_total, int $etat, int $delai, array $items)
    {
        $this->id = $id;
        $this->mail_client = $mail_client;
        $this->type_livraison = $type_livraison;
        $this->date = $date;
        $this->montant_total = $montant_total;
        $this->delai = $delai;
        $this->items = $items;
        $this->etat = $etat;
    }

}