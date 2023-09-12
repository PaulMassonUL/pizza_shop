<?php

namespace pizzashop\shop\domain\entities\catalogue;

class Taille extends \Illuminate\Database\Eloquent\Model
{

    const NORMALE = 1;
    const GRANDE = 2;
	
    protected $connection = 'catalog';
    protected $table = 'taille';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [ 'libelle'];

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'tarif', 'taille_id', 'produit_id');
    }

}