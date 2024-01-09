<?php

namespace pizzashop\catalog\domain\service\catalogue;

use pizzashop\catalog\domain\dto\catalogue\ProduitDTO;

interface iInfoCatalogue
{
    /**
     * @throws ServiceCatalogueNotFoundException
     */
    function getProduit(int $numero, int $taille): ProduitDTO;
}