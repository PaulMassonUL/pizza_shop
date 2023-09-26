<?php

namespace pizzashop\shop\domain\entities\commande;

use pizzashop\shop\domain\dto\commande\CommandeDTO;

class Commande extends \Illuminate\Database\Eloquent\Model
{
    const ETAT_CREE = 1;
    const ETAT_VALIDE = 2;

    const TYPE_LIVRAISON_SUR_PLACE = 1;
    const TYPE_LIVRAISON_DOMICILE = 2;
    const TYPE_LIVRAISON_A_EMPORTER = 3;

    protected $connection = 'command';
    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['id', 'date_commande', 'type_livraison', 'mail_client', 'etat'];

    public function items()
    {
        return $this->hasMany(Item::class, 'commande_id', 'id');
    }

    public function calculerMontantTotal(): void
    {
        $this->montant = 0;
        foreach ($this->items as $item) {
            $this->montant += $item->tarif * $item->quantite;
        }
    }

    public function toDTO(): CommandeDTO
    {
        return new CommandeDTO(
            $this->mail_client,
            $this->type_livraison,
            $this->date_commande,
        );
    }

}