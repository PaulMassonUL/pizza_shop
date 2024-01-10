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
}