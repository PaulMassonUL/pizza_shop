<?php

namespace pizzashop\shop\domain\dto\commande;

class CommandeDTO extends \pizzashop\shop\domain\dto\DTO
{

    public string $id;
    public string $date;
    public int $type_livraison;
    public string $mail_client;
    public float $montant;
    public int $delai;
    public array $items;

    public function __construct(string $mail_client, int $type_livraison, string $date)
    {
        $this->mail_client = $mail_client;
        $this->type_livraison = $type_livraison;
        $this->date = $date;
    }
}