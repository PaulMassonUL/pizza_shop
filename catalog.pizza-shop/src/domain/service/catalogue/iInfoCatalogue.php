<?php

namespace pizzashop\catalog\domain\service\catalogue;

use pizzashop\catalog\domain\dto\catalogue\ProduitDTO;

interface iInfoCatalogue
{
    /**
     * @throws ServiceCatalogueNotFoundException
     */
    public function getProduitById(int $id): ProduitDTO;

    public function getProduits(): array;

    public function getProduitsCommande(array $numerostailles): array;

    public function getProduitsCategorie(int $id_categorie): array;
}