<?php

namespace App\Models;

use App\Models\Readers\ExpertReader;
use App\Models\Writers\LogWriter;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Expert extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    /**
     * Overriden attribute for mass assignement.
     *
     * @access protected
     * @var String[]
     */
    protected $fillable = [
        'id',
        'password',
    ];

    //*********************
    //***    QUERIES    ***
    //*********************


    /**
     * The number of completed Assessments of this Expert.
     * @return int The number of completed Assessments.
     */
    public function getNbCompletedAssessments(): int
    {
        return Assignation::getCompletedAssessments()->where('expert_id', $this->id)->count();
    }

    /**
     * The number of remaining Assessments of this Expert.
     * @return int The number of remaining Assessments.
     */
    public function getNbRemainingAssessments(): int
    {
        return Assignation::getRemainingAssessments()->where('expert_id', $this->id)->count();
    }

    /**
     * The number of Assessments of this Expert.
     * @return int The number of Assessments.
     */
    public function getNbAssessments(): int
    {
        return Assignation::where('expert_id', $this->id)->count();
    }

    /**
     *  Containing all the distinct Topics id of this Expert in the DB.
     */
    public function getTopicsId()
    {
        return Assignation::where('expert_id', $this->id)->distinct()->pluck('topic_id');
    }

    /**
     * Return the advancement of the Expert in parameter on this Topic.
     *
     * @return array Returns the result in an Array.
     */
    public function getAdvancement(): array
    {
        return [
            'id' => $this->id,
            'nbTotal' => $this->getNbAssessments(),
            'nbCompleted' => $this->getNbCompletedAssessments(),
            'nbRemaining' => $this->getNbRemainingAssessments(),
            'isDone' => $this->hasFinished(),
        ];
    }

    /**
     * The number of completed Topics of this Expert.
     * @return int The number of completed Topics.
     */
    public function getNbCompletedTopics(): int
    {
        /**
         * Gets the collection of distinct topic_id of this Expert.
         * At this point, the number of completed topics = 0.
         */
        $topics_id_of_this_expert = $this->getTopicsId();
        $nb_completed_topic = 0;
        /**
         * For each topic assigned to this expert...
         */
        foreach ($topics_id_of_this_expert as $topic_id_of_this_expert) {
            /**
             * We get all the assignations about 1 topic of this expert.
             */
            $assessments_topicexpert = Assignation::getAssignationsOfExpertAboutTopic($this->id, $topic_id_of_this_expert);
            /**
             * How many there are?
             */
            $nb_assessments_topicexpert = $assessments_topicexpert->count();

            /**
             * How many are completed?
             */
            $nb_completed_assessments_topicexpert = $assessments_topicexpert->where('is_completed', true)->count();
            /**
             * If there are as many completed assignations as there are in total,
             * then it means that this topic is completed by this expert.
             */
            if ($nb_assessments_topicexpert === $nb_completed_assessments_topicexpert)
                $nb_completed_topic += 1;
        }

        return $nb_completed_topic;
    }

    /**
     * The number of remaining Topics of this Expert.
     * @param Expert $e The expert
     * @return int The number of remaining Topics.
     */
    public function getNbRemainingTopics(): int
    {
        return $this->getNbTopics() - $this->getNbCompletedTopics();
    }

    /**
     * The number of Topics of this Expert.
     * @return int The number of Topics.
     */
    public function getNbTopics(): int
    {
        /**
         * Get first the assignations of this Expert, then keeps the topic_id column, then
         * count the number of distinct topics there are.
         */
        return count($this->getTopicsId());
    }

    /**
     * @return bool true if this Expert has finished annotating all his assessments, false
     * otherwise.
     */
    public function hasFinished(): bool
    {
        return $this->getNbCompletedAssessments() === $this->getNbAssessments();
    }

    /**
     * A task is the set of assessments assigned to one Expert. A completed Task is
     * when an Expert has finished all his assigned assessments.
     * @return int The number of tasks completed by all the experts in the DB.
     */
    public static function getNbCompletedTasks(): int
    {
        $experts_with_tasks = Expert::all();
        $nb_completed_tasks = 0;
        foreach ($experts_with_tasks as $e) {
            if ($e->hasFinished())
                $nb_completed_tasks += 1;
        }
        return $nb_completed_tasks;
    }

    /**
     * @return int The number of remaining tasks in the DB.
     */
    public static function getNbRemainingTasks(): int
    {
        return self::getNbTasks() - self::getNbCompletedTasks();
    }

    /**
     * @return int The number of Tasks in the DB.
     */
    public static function getNbTasks(): int
    {
        return Expert::all()->count();
    }

    /**
     * Indicates if there is only one remaining assignation for this Expert.
     *
     * @return bool true if yes, false otherwise.
     */
    public function isLastAssignation(): bool
    {
        return $this->getNbRemainingAssessments() == 1;
    }

    /**
     * Gets the next assignation (the first that is not completed) of this Expert
     * sorted by Topic.
     *
     * @return Assignation|false Returns his next Assignation. May returns false if
     * the Expert has no next Assignation.
     */
    public function getNextAssignation(): Assignation|false
    {
        if ($this->hasFinished())
            return false;
        return Assignation::where('expert_id', $this->id)
            ->orderBy('topic_id')
            ->where('is_completed', false)
            ->first();
    }

    /**
     * The expert to whom we change his password.
     * @param Expert $e The expert.
     * @param String $pw The password.
     * @return bool true if the expert has got his password changed.
     */
    public static function changePassword(Expert $e, String $pw): bool
    {
        try {
            Expert::find($e->id)->update(
                ['password' => $pw]
            );
        } catch (Exception $ex) {
            LogWriter::addLog("Error while updating the password of " . $e->id . ". " . $ex->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Adds an expert to the DB.
     * @param String $id The id of the expert.
     * @param String $pw The password of the expert.
     * @return bool true if the expert has been added to the DB.
     */
    public static function addExpert(String $id, String $pw): bool
    {
        try {
            self::insert([
                'id' => $id,
                'password' => $pw,
            ]);
        } catch (Exception $ex) {
            LogWriter::addLog("Error while adding new expert : " . $id . ". " . $ex->getMessage());
            return false;
        }
        return true;
    }

    //*********************
    //***    TABLES     ***
    //*********************

    /**
     * Creates the experts table.
     */
    public static function createTable(): void
    {
        Schema::create('experts', function (Blueprint $table) {
            /**
             * Primary key.
             */
            $table->primary('id');
            /**
             * Keys.
             */
            $table->string('id');
            $table->string('password');
        });
    }

    /**
     * Calls the parser to get the content from the experts file.
     *
     * @return array The array containing the data.
     */
    public static function getContentFromFile(): array
    {
        return ExpertReader::parseFile(Settings::$RESSOURCES_PATH . Settings::$EXPERT_FILENAME);
    }

    /**
     * Fills the experts table.
     *
     * @param array $content The array containing the data to fill up the table.
     */
    public static function fillTable(array $content): void
    {
        foreach ($content as $key => $value) {
            $id = $value['id'];
            /**
             * If the $id value already refers to an Expert, then
             * its password is updated.
             */
            Expert::upsert([
                'id' => $id,
                'password' => $value['password'],
            ],
            ['id'], ['password']);
        }
    }

    //*********************
    //***   RELATIONS   ***
    //*********************

    public function assignations()
    {
        return $this->hasMany(Assignation::class, 'expert_id');
    }
}
