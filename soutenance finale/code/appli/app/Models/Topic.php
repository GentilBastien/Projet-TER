<?php

namespace App\Models;

use App\Models\Readers\TopicReader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Topic extends Model
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
        'id',
        'keywords',
        'conversational',
        'explanation',
    ];

    //*********************
    //***    QUERIES    ***
    //*********************

    /**
     * Return the advancement of the Expert in parameter on this Topic.
     *
     * @param Expert $e The Expert.
     * @return array Returns the result in an Array.
     */
    public function getAdvancementOf(Expert $e): array
    {
        $assessments = Assignation::getAssignationsOfExpertAboutTopic($e->id, $this->id);
        $nb_assessments = $assessments->count();
        $nb_completed_assessments = $assessments->where('is_completed', true)->count();
        $nb_remaining_assessments = $nb_assessments - $nb_completed_assessments;

        return [
            'id' => $this->id,
            'conversational' => $this->conversational,
            'nbTotal' => $nb_assessments,
            'nbCompleted' => $nb_completed_assessments,
            'nbRemaining' => $nb_remaining_assessments,
            'isCompleted' => $nb_remaining_assessments == 0,
        ];
    }

        /**
     * Return the advancement of this Topic.
     *
     * @return array Returns the result in an Array.
     */
    public function getAdvancement(): array
    {
        $assessments = Assignation::getAssignationsAbout($this->id);
        $nb_assessments = $assessments->count();
        $nb_completed_assessments = $assessments->where('is_completed', true)->count();
        $nb_remaining_assessments = $nb_assessments - $nb_completed_assessments;

        return [
            'id' => $this->id,
            'conversational' => $this->conversational,
            'nbTotal' => $nb_assessments,
            'nbCompleted' => $nb_completed_assessments,
            'nbRemaining' => $nb_remaining_assessments,
            'isCompleted' => $nb_remaining_assessments == 0,
        ];
    }

    public function isCompleted(): bool
    {
        $about_me = Assignation::getAssignationsAbout($this->id);
        return $about_me->count() == $about_me->where('is_completed', true)->count();
    }

    /**
     * @return int The number of completed Topics in the DB.
     */
    public static function getNbCompletedTopics(): int
    {
        $topics = Topic::all();
        $nb_completed_topic = 0;
        foreach ($topics as $topic)
            $nb_completed_topic += $topic->isCompleted() ? 1 : 0;
        return $nb_completed_topic;
    }

    /**
     * @return int The number of remaining Topics in the DB.
     */
    public static function getNbRemainingTopics(): int
    {
        return self::getNbTopics() - self::getNbCompletedTopics();
    }

    /**
     * @return int The number of Topics in the DB.
     */
    public static function getNbTopics(): int
    {
        return Topic::all()->count();
    }

    //*********************
    //***    TABLES     ***
    //*********************

    /**
     * Creates the topics table.
     */
    public static function createTable(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            /**
             * Primary key.
             */
            $table->id();
            /**
             * Keys.
             */
            $table->string('keywords');
            $table->string('conversational');
            $table->string('explanation');
        });
    }

    /**
     * Calls the parser to get the content from the topics file.
     *
     * @return array The array containing the data.
     */
    public static function getContentFromFile(): array
    {
        return TopicReader::parseFile(Settings::$RESSOURCES_PATH . Settings::$TOPIC_FILENAME);
    }

    /**
     * Fills the topics table.
     *
     * @param array $content The array containing the data to fill up the table.
     */
    public static function fillTable(array $content): void
    {
        foreach ($content as $key => $value) {
            self::insert([
                'id' => $value['id'],
                'keywords' => $value['keywords'],
                'conversational' => $value['conversational'],
                'explanation' => $value['explanation'],
            ]);
        }
    }

    //*********************
    //***   RELATIONS   ***
    //*********************

    public function snippets()
    {
        return $this->hasMany(Snippet::class, 'topic_id');
    }

    public function assignations()
    {
        return $this->hasMany(Assignation::class, 'topic_id');
    }
}
