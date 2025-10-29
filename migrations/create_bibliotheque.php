<?php
require '../vendor/autoload.php';

use MongoDB\Client;
use Faker\Factory as Faker;

class CreateBibliothequeMigration
{
    private $client;
    private $db;
    private $faker;

    public function __construct()
    {
        $this->client = new Client("mongodb://127.0.0.1:27017");
        $this->db = $this->client->bibliotheque;
        $this->faker = Faker::create('fr_FR');
    }

    public function up()
    {
        echo "🚀 Création de la base 'bibliotheque'...\n";

        // --- AUTEURS ---
        $auteurs = [];
        for ($i = 1; $i <= 50; $i++) {
            $auteurs[] = [
                "_id" => $i,
                "nom" => $this->faker->name(),
                "nationalite" => $this->faker->randomElement(["Française", "Britannique", "Américaine", "Japonaise", "Italienne", "Espagnole", "Canadienne"]),
                "dateNaissance" => $this->faker->date("Y-m-d", "1990-01-01")
            ];
        }
        $this->db->auteurs->insertMany($auteurs);

        // --- LIVRES ---
        $genres = ["Science-fiction", "Fantastique", "Horreur", "Philosophie", "Historique", "Romance", "Policier", "Biographie", "Essai"];
        $livres = [];
        for ($i = 1; $i <= 500; $i++) {
            $livres[] = [
                "_id" => $i,
                "titre" => ucfirst($this->faker->words($this->faker->numberBetween(2, 5), true)),
                "annee" => $this->faker->year(),
                "genre" => $this->faker->randomElement($genres),
                "auteur_id" => $this->faker->numberBetween(1, 50),
                "disponible" => $this->faker->boolean(75) // 75% de livres disponibles
            ];
        }
        $this->db->livres->insertMany($livres);

        // --- LIVRES SANS AUTEURS ---
        $livresSansAuteurs = [];
        for ($i = 501; $i <= 510; $i++) {
            $livresSansAuteurs[] = [
                "_id" => $i,
                "titre" => ucfirst($this->faker->words($this->faker->numberBetween(2, 5), true)),
                "annee" => $this->faker->year(),
                "genre" => $this->faker->randomElement($genres),
                "disponible" => $this->faker->boolean(75) // 75% de livres disponibles
                // Note : pas de champ 'auteur_id'
            ];
        }
        $this->db->livres->insertMany($livresSansAuteurs);

        // --- LECTEURS ---
        $lecteurs = [];
        for ($i = 1; $i <= 200; $i++) {
            $lecteurs[] = [
                "_id" => $i,
                "nom" => $this->faker->name(),
                "email" => $this->faker->unique()->safeEmail(),
                "abonnement" => $this->faker->randomElement(["standard", "premium"]),
                "ville" => $this->faker->city(),
                "inscription" => new MongoDB\BSON\UTCDateTime($this->faker->dateTimeBetween("-3 years", "now")->getTimestamp() * 1000)
            ];
        }
        $this->db->lecteurs->insertMany($lecteurs);

        // --- EMPRUNTS ---
        $emprunts = [];
        for ($i = 1; $i <= 1000; $i++) {
            $dateEmprunt = $this->faker->dateTimeBetween("-1 years", "now");
            $dateRetour = (rand(0, 1)) ? $this->faker->dateTimeBetween($dateEmprunt, "now") : null;

            $emprunts[] = [
                "_id" => $i,
                "lecteur_id" => $this->faker->numberBetween(1, 200),
                "livre_id" => $this->faker->numberBetween(1, 500),
                "date_emprunt" => new MongoDB\BSON\UTCDateTime($dateEmprunt->getTimestamp() * 1000),
                "date_retour" => $dateRetour ? new MongoDB\BSON\UTCDateTime($dateRetour->getTimestamp() * 1000) : null,
                "longe" => $this->faker->boolean(20)
            ];
        }
        $this->db->emprunts->insertMany($emprunts);
        echo "✅ Base 'bibliotheque' créée avec succès.\n";
    }

    public function down()
    {
        $this->client->dropDatabase('bibliotheque');
        echo "🗑️ Base 'bibliotheque' supprimée.\n";
    }
}

// --- EXÉCUTION ---
$migration = new CreateBibliothequeMigration();
$migration->up();
// Pour supprimer : $migration->down();
