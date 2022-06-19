<?php

namespace App\Models;

use App\Models\Readers\GlobalTagReader;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GlobalTag extends Model
{
    use HasFactory;
    protected $table = 'global_tags';
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
     * @return Collection The collection of GlobalTag in this DB.
     */
    public static function getGlobalTags(): Collection
    {
        return GlobalTag::all();
    }

    /**
     * @return int The number of global tags in the DB.
     */
    public static function getNbGlobalTags(): int
    {
        return WordTag::count();
    }

    /**
     * Writes the globaltags in a full string.
     */
    public static function writeGlobalTags(): String
    {
        $res = "const GlobalTags = {" . PHP_EOL;
        foreach (self::getGlobalTags() as $global_tag) {
            $res = $res . "\"" . $global_tag->tag_name . "\": new GlobalTag(" . $global_tag->value . ", \"" . $global_tag->tag_name . "\", \"#" . $global_tag->color . "\")," . PHP_EOL;
        }
        return $res . "}";
    }

    //*********************
    //***    TABLES     ***
    //*********************

    /**
     * Creates the global tag table.
     */
    public static function createTable(): void
    {
        Schema::create('global_tags', function (Blueprint $table) {
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
     * Calls the parser to get the content from the global tags file.
     *
     * @return array The array containing the data.
     */
    public static function getContentFromFile(): array
    {
        return GlobalTagReader::parseFile(Settings::$RESSOURCES_PATH . Settings::$GLOBAL_FILENAME);
    }

    /**
     * Fills the global tags table.
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

    public function globalAssessments()
    {
        return $this->hasMany(GlobalAssessment::class, 'annotation');
    }
}
