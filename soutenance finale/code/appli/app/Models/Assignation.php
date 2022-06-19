<?php

namespace App\Models;

use App\Models\Readers\AssignationReader;
use App\Models\Writers\LogWriter;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Assignation extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    /**
     * Overriden attribute for mass assignement.
     *
     * @access protected
     * @var String[]
     */
    protected $fillable = [
        'expert_id',
        'topic_id',
        'document_id',
        'is_completed',
    ];

    //*********************
    //***    QUERIES    ***
    //*********************

    /**
     * @return Collection The view of completed Assignations.
     */
    public static function getCompletedAssessments(): Collection
    {
        return Assignation::where('is_completed', true)->get();
    }

    /**
     * @return Collection The view of remaining Assignations.
     */
    public static function getRemainingAssessments(): Collection
    {
        return Assignation::where('is_completed', false)->get();
    }

    /**
     * @return int The number of completed Assignations in the DB.
     */
    public static function getNbCompletedAssessments(): int
    {
        return self::getCompletedAssessments()->count();
    }

    /**
     * @return int The number of remaining Assessments in the DB.
     */
    public static function getNbRemainingAssessments(): int
    {
        return self::getRemainingAssessments()->count();
    }

    /**
     * @return int The number of Assessments in the DB.
     */
    public static function getNbAssessments(): int
    {
        return Assignation::count();
    }

    /**
     * Adds a new Assignation into the assignations table.
     *
     * @param int $expert_id The id of the expert.
     * @param int $topic_id The id of the topic.
     * @param int $document_id The id of the document.
     * @return bool true if the add operation has been a success, false otherwise.
     */
    public static function addAssignation(int $expert_id, int $topic_id, int $document_id): bool
    {
        try {
            self::insert([
                'expert_id' => $expert_id,
                'topic_id' => $topic_id,
                'document_id' => $document_id,
                'is_completed' => false,
            ]);
        } catch (Exception $e) {
            LogWriter::addLog("Error while adding a new assignation! " . $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Gets all the assignations of the Expert in parameter.
     *
     * @param Expert $e The Expert.
     * @return Builder The collection of Assignation of this expert.
     */
    public static function getAssignationsOf(String $expert_id): Builder
    {
        return Assignation::where('expert_id', $expert_id);
    }

    /**
     * Gets all the assignations of a Topic in parameter.
     *
     * @param Topic $t The Topic.
     * @return Builder The collection of Assignation about this Topic.
     */
    public static function getAssignationsAbout(int $topic_id): Builder
    {
        return Assignation::where('topic_id', $topic_id);
    }

    /**
     * Gets all the assignations of a Document in parameter.
     *
     * @param Document $d The Document.
     * @return Builder The collection of Assignation on this Document.
     */
    public static function getAssignationsOn(Document $d): Builder
    {
        return Assignation::where('document_id', $d->id);
    }

    public static function getAssignationsOfExpertAboutTopic(String $expert_id, int $topic_id): Builder
    {
        return Assignation::where('expert_id', $expert_id)->where('topic_id', $topic_id);
    }

    //*********************
    //***    TABLES     ***
    //*********************

    /**
     * Creates the assignations table.
     */
    public static function createTable(): void
    {
        Schema::create('assignations', function (Blueprint $table) {
            /**
             * Primary key.
             */
            $table->id();
            /**
             * Keys.
             */
            $table->bigInteger('expert_id');
            $table->bigInteger('topic_id');
            $table->bigInteger('document_id');
            $table->boolean('is_completed');
            /**
             * Foreign keys.
             */
            $table->foreign('expert_id')->references('id')->on('experts');
            $table->foreign('topic_id')->references('id')->on('topics');
            $table->foreign('document_id')->references('id')->on('documents');
        });
    }

    /**
     * Calls the parser to get the content from the assignations file.
     *
     * @return array The array containing the data.
     */
    public static function getContentFromFile(): array
    {
        return AssignationReader::parseFile(Settings::$RESSOURCES_PATH . Settings::$ASSIGNATION_FILENAME);
    }

    /**
     * Fills the assignations table. And according to that, the GlobalAssessment
     * and WordAssessment tables.
     *
     * @param array $content The array containing the data to fill up the table.
     */
    public static function fillTable(array $content): void
    {
        foreach ($content as $key => $value) {
            $expert_id = $value['expert_id'];
            $topic_id = $value['topic_id'];
            $document_id = $value['document_id'];
            $id = strlen($expert_id) * $topic_id + $document_id * random_int(1, 10);
            /**
             * Inserts new records in the Assignation table
             */
            self::insert([
                /**
                 * Hash the concat of expert_id.topic_id.document_id to have a numeric value of id.
                 */
                'id' => $id,
                'expert_id' => $expert_id,
                'topic_id' => $topic_id,
                'document_id' => $document_id,
                'is_completed' => false,
            ]);

            GlobalAssessment::fillTable($id);
            WordAssessment::fillTable($id, $topic_id, $document_id);
        }
    }

    /**
     * Creates a unique Stringinteger from a String.
     *
     * @param String $str The string to parse.
     * @return String The String.
     */
    private static function ordord(String $str): String
    {
        $res = "";
        for ($i = 0; $i < strlen($str); $i++)
            $res = $res . ord($str[$i]);
        return $res;
    }

    //*********************
    //***   RELATIONS   ***
    //*********************

    public function expert()
    {
        return $this->belongsTo(Expert::class, 'expert_id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function globalAssessment()
    {
        return $this->hasOne(GlobalAssessment::class, 'assignation_id');
    }

    public function wordAssessments()
    {
        return $this->hasMany(WordAssessment::class, 'assignation_id');
    }
}
