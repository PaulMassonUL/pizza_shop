<?php

namespace pizzashop\shop\domain\entities\commande;

use pizzashop\shop\domain\dto\commande\ItemDTO;

class Item extends \Illuminate\Database\Eloquent\Model
{
    protected $connection = 'commande';
    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $fillable = ['numero', 'libelle', 'taille', 'libelle_taille', 'tarif', 'quantite'];

    public function commande() {
        return $this->belongsTo(Commande::class, 'commande_id', 'id');
    }

    public function toDTO() : ItemDTO {
        $itemDTO = new ItemDTO(
            $this->numero,
            $this->taille,
            $this->quantite
        );
        $itemDTO->setLibelle($this->libelle);
        $itemDTO->setTarif($this->tarif);
        $itemDTO->setLibelleTaille($this->libelle_taille);
        return $itemDTO;
    }
}