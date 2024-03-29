<?php

namespace pizzashop\catalog\domain\entities\catalogue;

use pizzashop\catalog\domain\dto\catalogue\ProduitDTO;

class Produit extends \Illuminate\database\eloquent\Model
{

    protected $connection = 'catalog';
    protected $table = 'produit';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['numero', 'libelle', 'description', 'image'];

    public function categorie(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function tailles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Taille::class, 'tarif', 'produit_id', 'taille_id')
            ->withPivot('tarif');
    }

    public function toDTO(): ProduitDTO
    {
        $produitDTO = new ProduitDTO(
            $this->id,
            $this->numero,
            $this->libelle,
            $this->description
        );
        $produitDTO->tailles = $this->tailles()->get()->toArray();
        $produitDTO->categorie = $this->categorie()->get()->toArray();
        $produitDTO->image = $this->image;
        return $produitDTO;
    }

}