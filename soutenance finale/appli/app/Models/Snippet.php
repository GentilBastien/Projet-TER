<?php

namespace App\Models;

use App\Models\Readers\SnippetReader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A snippet is a document with only a part of text generated
 * according to the topic.
 */
class Snippet extends Document
{
    use HasFactory;

    public $incrementing = true;
    public $timestamps = false;

    /**
     * Overriden attribute for mass assignement.
     *
     * @access protected
     * @var String[]
     */
    protected $fillable = [
        'topic_id',
        'document_id',
        'title',
        'abstract',
    ];

    public function __construct()
    {
        /**
         * Call Document's constructor.
         */
        parent::__construct();
    }

    //*********************
    //***    QUERIES    ***
    //*********************


    //*********************
    //***    TABLES     ***
    //*********************

    /**
     * Creates the snippets table.
     */
    public static function createTable(): void
    {
        Schema::create('snippets', function (Blueprint $table) {
            /**
             * Primary key.
             */
            $table->primary(['topic_id', 'document_id']);
            /**
             * Keys.
             */
            $table->bigInteger('topic_id'); //* composite key
            $table->bigInteger('document_id'); //* composite key
            $table->string('title');
            $table->string('abstract');
            /**
             * Foreign keys.
             */
            $table->foreign('topic_id')->references('id')->on('topics');
            $table->foreign('document_id')->references('id')->on('documents');
        });
    }

    /**
     * Calls the parser to get the content from the snippets file.
     *
     * @return array The array containing the data.
     */
    public static function getContentFromFile(): array
    {
        return SnippetReader::parseFile(Settings::$RESSOURCES_PATH . Settings::$SNIPPET_FILENAME);
    }

    /**
     * Fills the snippets table.
     *
     * @param array $content The array containing the data to fill up the table.
     */
    public static function fillTable(array $content): void
    {
        foreach ($content as $key => $value) {
            $topic_id = $value['topic_id'];
            $document_id = $value['document_id'];
            Snippet::insert([
                'topic_id' => $topic_id,
                'document_id' => $document_id,
                'title' => $value['title'],
                'abstract' => $value['abstract'],
            ]);
        }
    }

    //*********************
    //***   RELATIONS   ***
    //*********************

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    
    //*********************
    //***    SETTERS    ***
    //*********************

    /**
     * Set the content of this Snippet from the abstract. Should not be
     * used after initialization.
     */
    protected function __setContent()
    {
        $this->_content = $this->abstract;
    }

    //*********************
    //***    GETTERS    ***
    //*********************

    /**
     * Better code to get a topic from its composite primary key.
     */
    public static function getSnippet(int $topic_id, int $document_id) : Snippet
    {
        return Snippet::where('topic_id', $topic_id)->where('document_id', $document_id)->first();
    }
}
