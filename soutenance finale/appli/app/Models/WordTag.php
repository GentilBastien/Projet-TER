<?php

namespace App\Models;

use App\Models\Readers\WordTagReader;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WordTag extends Model
{
    use HasFactory;
    protected $table = 'words_tags';
    protected $primaryKey = 'value';
    public $timestamps = false;

    /**
     * Overriden attribute for mass assignement.
     *
     * @access protected
     * @var String[]
     */
    protected $fillable = [
        'value',
        'tag_name',
        'color',
    ];

    //*********************
    //***    QUERIES    ***
    //*********************

    /**
     * @return Collection The collection of WordTag in this DB.
     */
    public static function getWordTags(): Collection
    {
        return WordTag::all();
    }

    /**
     * @return int The number of word tags in the DB.
     */
    public static function getNbWordTags(): int
    {
        return WordTag::count();
    }

    /**
     * Writes the wordtags in a full string.
     */
    public static function writeWordTags(): String
    {
        $res = "const WordTags = {" . PHP_EOL;
        foreach (self::getWordTags() as $word_tag) {
            $res = $res . "\"" . $word_tag->tag_name . "\": new WordTag(" . $word_tag->value . ", \"" . $word_tag->tag_name . "\", \"#" . $word_tag->color . "\")," . PHP_EOL;
        }
        return $res . "}";
    }

    //*********************
    //***    TABLES     ***
    //*********************

    /**
     * Creates the word tags table.
     */
    public static function createTable(): void
    {
        Schema::create('words_tags', function (Blueprint $table) {
            /**
             * Primary key.
             */
            $table->primary('value');
            /**
             * Keys.
             */
            $table->integer('value');
            $table->string('tag_name');
            $table->string('color');
        });
    }

    /**
     * Calls the parser to get the content from the words tags file.
     *
     * @return array The array containing the data.
     */
    public static function getContentFromFile(): array
    {
        return WordTagReader::parseFile(Settings::$RESSOURCES_PATH . Settings::$WORD_FILENAME);
    }

    /**
     * Fills the words tags table.
     *
     * @param array $content The array containing the data to fill up the table.
     */
    public static function fillTable(array $content): void
    {
        foreach ($content as $key => $value) {
            self::insert([
                'value' => $value['value'],
                'tag_name' => $value['tag_name'],
                'color' => $value['color'],
            ]);
        }
    }

    //*********************
    //***   RELATIONS   ***
    //*********************

    public function wordAssessments()
    {
        return $this->hasMany(WordAssessment::class, 'annotation');
    }
}
