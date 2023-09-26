<?php

namespace pizzashop\shop\domain\service\catalogue;

use pizzashop\shop\domain\dto\catalogue\ProduitDTO;

interface iInfoCatalogue
{
    /**
     * @throws ServiceCatalogueNotFoundException
     */
    function getProduit(int $numero, int $taille): ProduitDTO;
}