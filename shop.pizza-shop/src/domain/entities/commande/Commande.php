<?php

namespace pizzashop\shop\domain\entities\commande;

use pizzashop\shop\domain\dto\commande\CommandeDTO;

class Commande extends \Illuminate\Database\Eloquent\Model
{
    const ETAT_CREE = 1;
    const ETAT_VALIDE = 2;

    protected $connection = 'command';
    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [ 'id', 'date_commande', 'type_livraison', 'mail_client', 'etat'];

    public function toDTO() : CommandeDTO {
        return new CommandeDTO(
            $this->id,
            $this->date_commande,
            $this->type_livraison,
            $this->mail_client,
            $this->montant,
            $this->delai
        );
    }

}