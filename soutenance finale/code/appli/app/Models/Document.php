<?php

namespace App\Models;

use App\Models\Readers\DocumentReader;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Document extends Assessment
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
        'external_url',
        'internal_uri',
    ];

    public function __construct()
    {
        /**
         * Call Assessment's constructor.
         */
        parent::__construct();
    }

    //*********************
    //***    TABLES     ***
    //*********************

    /**
     * Creates the documents table.
     */
    public static function createTable(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            /**
             * Primary key.
             */
            $table->id();
            /**
             * Keys.
             */
            $table->string('external_url')->nullable();
            $table->string('internal_uri')->nullable();
        });
    }

    /**
     * Calls the parser to get the content from the documents file.
     *
     * @return array The array containing the data.
     */
    public static function getContentFromFile(): array
    {
        return DocumentReader::parseFile(Settings::$RESSOURCES_PATH . Settings::$DOCUMENT_FILENAME);
    }

    /**
     * Fills the documents table.
     *
     * @param array $content The array containing the data to fill up the table.
     */
    public static function fillTable(array $content): void
    {
        foreach ($content as $key => $value) {
            $id = $value['id'];
            self::insert([
                'id' => $id,
                'external_url' => $value['external_url'],
                'internal_uri' => $value['internal_uri'],
            ]);
        }
    }

    //*********************
    //***   RELATIONS   ***
    //*********************

    public function snippets()
    {
        return $this->hasMany(Snippet::class, 'document_id');
    }

    public function assignations()
    {
        return $this->hasMany(Assignation::class, 'document_id');
    }

    //*********************
    //***    SETTERS    ***
    //*********************

    /**
     * Set the content of this Document from the internal URI or the
     * external URL. Should not be used after initialization.
     */
    protected function __setContent()
    {
        $this->_content = file_get_contents($this->internal_uri);
        if (!$this->_content)
            $this->_content = file_get_contents($this->external_url);
        if (!$this->_content)
            throw new Exception("Could not read the content of a document.");
    }
}
