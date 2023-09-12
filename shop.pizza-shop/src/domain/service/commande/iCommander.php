<?php

namespace pizzashop\shop\domain\service\commande;

use pizzashop\shop\domain\dto\commande\CommandeDTO;

interface iCommander
{
    function creerCommande(CommandeDTO $c) : void;

    function validerCommande(string $id) : void;

    function getCommande(string $id) : CommandeDTO;
}