<?php

namespace pizzashop\tests\commande;

use Faker\Factory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use pizzashop\shop\domain\dto\commande\ItemDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\entities\commande\Item;
use Illuminate\Database\Capsule\Manager as DB;
use pizzashop\shop\domain\service\commande\ServiceCommandeNotFoundException;

class ServiceCommandeTest extends \PHPUnit\Framework\TestCase
{

    private static $commandeIds = [];
    private static $itemIds = [];
    private static $serviceProduits;
    private static $serviceCommande;
    private static $faker;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        $dbcom = __DIR__ . '/../../config/commande.db.test.ini';
        $dbcat = __DIR__ . '/../../config/catalog.db.ini';
        $db = new DB();
        $db->addConnection(parse_ini_file($dbcom), 'commande');
        $db->addConnection(parse_ini_file($dbcat), 'catalog');
        $db->setAsGlobal();
        $db->bootEloquent();

        self::$serviceProduits = new \pizzashop\shop\domain\service\catalogue\ServiceCatalogue();
        $logger = new Logger('app.errors');
        $logger->pushHandler(new StreamHandler('./tests/commande/test.log'));
        self::$serviceCommande = new \pizzashop\shop\domain\service\commande\ServiceCommande(self::$serviceProduits, $logger);
        self::$faker = Factory::create('fr_FR');
        self::fill();

    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDown();
        self::cleanDB();
    }


    private static function cleanDB()
    {
        foreach (self::$commandeIds as $id) {
            Commande::find($id)->delete();
        }
        foreach (self::$itemIds as $id) {
            Item::find($id)->delete();
        }
    }

    private static function fill()
    {
        $commande = Commande::create([
            'id' => self::$faker->uuid,
            'date_commande' => self::$faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
            'type_livraison' => Commande::TYPE_LIVRAISON_DOMICILE,
            'mail_client' => self::$faker->email,
            'etat' => Commande::ETAT_CREE
        ]);
        self::$commandeIds[] = $commande->id;

        for ($i = 0; $i < self::$faker->numberBetween(1, 5); $i++) {
            $item = Item::create([
                'id' => self::$faker->uuid,
                'numero' => $i + 1,
                'libelle' => self::$faker->word,
                'taille' => self::$faker->numberBetween(1, 2),
                'libelle_taille' => self::$faker->word,
                'tarif' => self::$faker->randomFloat(2, 5, 20),
                'quantite' => self::$faker->numberBetween(1, 5),
                'commande_id' => $commande->id
            ]);
            self::$itemIds[] = $item->id;
        }
    }


    /**
     * @throws ServiceCommandeNotFoundException
     */
    public function testAccederCommande()
    {
        foreach (self::$commandeIds as $id) {
            $commandeEntity = Commande::find($id);
            $commandeDTO = self::$serviceCommande->accederCommande($id);
            $this->assertNotNull($commandeDTO);

            // TODO : comparer les données de l'entité et du DTO
            $this->assertEquals($commandeEntity->id, $commandeDTO->id);
        }
    }

    public function testCreerCommand()
    {
        $commandeDTO = self::$serviceCommande->creerCommande(new \pizzashop\shop\domain\dto\commande\CommandeDTO(
            self::$faker->uuid,
            self::$faker->email,
            Commande::TYPE_LIVRAISON_DOMICILE,
            self::$faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
            self::$faker->randomFloat(2, 5, 20),
            Commande::ETAT_CREE,
            0,
            array(
                new ItemDTO(
                    self::$faker->numberBetween(1, 5),
                    self::$faker->numberBetween(1, 2),
                    self::$faker->numberBetween(1, 5)
                )
            )
        ));
        $this->assertNotNull($commandeDTO);
        $this->assertNotNull(Commande::find($commandeDTO->id));
        self::$commandeIds[] = $commandeDTO->id;
    }

    public function testValiderCommande()
    {
        $id = self::$commandeIds[0];
        $commandeDTO = self::$serviceCommande->validerCommande($id);
        $this->assertNotNull($commandeDTO);
        $this->assertEquals(Commande::ETAT_VALIDE, $commandeDTO->etat);
    }

}