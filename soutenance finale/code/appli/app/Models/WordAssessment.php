<?php

namespace App\Models;

use App\Models\Writers\LogWriter;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WordAssessment extends Model
{
    use HasFactory;

    protected $table = 'words_assessments';
    public $timestamps = false;

    /**
     * Overriden attribute for mass assignement.
     *
     * @access protected
     * @var String[]
     */
    protected $fillable = [
        'assignation_id',
        'word',
        'indice',
        'annotation',
    ];

    //*********************
    //***    QUERIES    ***
    //*********************

    /**
     * @param int $assignation_id An assignation id.
     * @param int $indice An index in the abstract.
     * @return WordAssessment The WordAssessment
     */
    public static function getWordAssessment(int $assignation_id, int $indice): WordAssessment
    {
        return WordAssessment::where('assignation_id', $assignation_id)
            ->where('indice', $indice)
            ->first();
    }

    /**
     * @param int $assignation_id An assignation id.
     * @return Collection The WordAssessments
     */
    public static function getWordsAssessment(int $assignation_id): Collection
    {
        return WordAssessment::where('assignation_id', $assignation_id)->get();
    }

    //*********************
    //***    TABLES     ***
    //*********************

    /**
     * Creates the word assessments table.
     */
    public static function createTable(): void
    {
        Schema::create('words_assessments', function (Blueprint $table) {
            /**
             * Primary key.
             */
            $table->primary(['assignation_id', 'indice']);
            /**
             * Keys.
             */
            $table->bigInteger('assignation_id'); //* composite key
            $table->string('word');
            $table->integer('indice'); //* composite key
            $table->integer('annotation');
            /**
             * Foreign keys.
             */
            $table->foreign('assignation_id')->references('id')->on('assignations');
            $table->foreign('annotation')->references('value')->on('words_tags');
        });
    }

    /**
     * Fills the words_assessments table with default values expecting future updates.
     * Every words of every assessment must be set to the lowest rating level in word tag.
     *
     * @param int $assignation_id
     * @param int $topic_id
     * @param int $document_id
     */
    public static function fillTable(int $assignation_id, int $topic_id, int $document_id): void
    {
        $target = Campaign::getInstance()->getTarget();
        $default_word_tag = WordTag::min('value');
        /**
         * Get the corresponding assessment.
         * For that, we get to know if we are looking for a snippet or a document.
         */
        if ($target == "documents")
            $assessment = Document::find($document_id);
        if ($target == "snippets")
            $assessment = Snippet::getSnippet($topic_id, $document_id);

        
        $words = explode(' ', $assessment->getAnnotableAbstract());

        foreach ($words as $indice => $word) {
            /**
             * We put a default word assessment for every words of the Assessment.
             */
            WordAssessment::insert([
                'assignation_id' => $assignation_id,
                'word' => $word,
                'indice' => $indice,
                'annotation' => $default_word_tag,
            ]);
        }
    }

    //*********************
    //***   RELATIONS   ***
    //*********************

    public function assignation()
    {
        return $this->belongsTo(Assignation::class, 'assignation_id');
    }

    public function annotation()
    {
        return $this->belongsTo(WordTag::class, 'annotation');
    }
}
