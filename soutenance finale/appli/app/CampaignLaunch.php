<?php

namespace App;

use App\Models\Admin;
use App\Models\Assignation;
use App\Models\Campaign;
use App\Models\Document;
use App\Models\Expert;
use App\Models\GlobalAssessment;
use App\Models\GlobalTag;
use App\Models\Snippet;
use App\Models\Topic;
use App\Models\WordAssessment;
use App\Models\WordTag;
use App\Models\Writers\LogWriter;
use Exception;

class CampaignLaunch
{
    private static $_instance;

    /**
     * Get the only instance of CampaignLaunch. If it doesn't
     * exist yet, creates it.
     *
     * @return CampaignLaunch The only instance of CampaignLaunch.
     */
    public static function getInstance(): CampaignLaunch
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new CampaignLaunch;
        }

        return self::$_instance;
    }

    /**
     * Private constructor. CampaignLaunch is Singleton and can't be instantiated more than once.
     * The only possible way to get a public instance of the single instantiated CampaignLaunch object
     * is from the static call of CampaignLaunch::getInstance().
     */
    private function __construct()
    {
        // if (Schema::hasTable('campaigns')) {
        //
        // }

        // 1) on vérifie que la bd n’existe pas déjà. Si elle existe -> push error msg dans les logs "DB with a same name already exists!"
        // -> à faire plus tard quand on aura une bdd distante.

        // 2) on vérifie que tous les fichiers existent. Si un seul est manquant, on arrête tout et on affiche un msg d'erreur dans les logs qui indique quel fichier est manquant.
        //-> c'est fait dans chaque méthode "parseFile" où on check si le fichier existe avant de le lire.

        // 3) on vérifie que les fichiers XML sont associés à une DTD qu'ils respectent, sinon on arrete et on affiche le msg d'erreur correspondant.
        //-> ça je pense qu'on le fait pas dynamiquement avec le code. C'est un truc qui se fait à part genre les fichiers XML sont accompagnés de leur DTD

        // 4) ensuite on parse tous les fichiers, on vérifie qu'aucune erreur intervient pdt cette opération sinon -> msg log
        try {
            $campaign_data = Campaign::getContentFromFile();
            $expert_data = Expert::getContentFromFile();
            $admin_data = Admin::getContentFromFile();
            $topic_data = Topic::getContentFromFile();
            $document_data = Document::getContentFromFile();
            $snippet_data = Snippet::getContentFromFile();
            $assignation_data = Assignation::getContentFromFile();
            $globaltag_data = GlobalTag::getContentFromFile();
            $wordtag_data = WordTag::getContentFromFile();

            // 5) puis on construit la BDD avec comme nom : jour date heure de création (pour mieux connaitre les versions)
            //on met le nom de la campagne dans le nom du fichier
            //pour faire l'étape 1), on regarde si une base existante contient ce nom de campagne "indifféremment du jour/date/heure"


            // 6) On construit toutes les tables
            Campaign::createTable();
            Expert::createTable();
            Admin::createTable();
            Topic::createTable();
            Document::createTable();
            Snippet::createTable();
            Assignation::createTable();
            GlobalTag::createTable();
            WordTag::createTable();
            GlobalAssessment::createTable();
            WordAssessment::createTable();

            // 7) et enfin on fill toutes les tables en initialisant les attributs de tables
            Campaign::fillTable($campaign_data);
            Expert::fillTable($expert_data);
            Admin::fillTable($admin_data);
            Topic::fillTable($topic_data);
            Document::fillTable($document_data);
            Snippet::fillTable($snippet_data);
            GlobalTag::fillTable($globaltag_data);
            WordTag::fillTable($wordtag_data);
            Assignation::fillTable($assignation_data);
        } catch (Exception $e) {
            /**
             * If any Exception is caught, we write it in the logs.
             */
            LogWriter::addLog("Error : " . $e->getMessage());
        }
    }
}
