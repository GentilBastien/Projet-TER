<?php

namespace App\Models;

use App\Models\Readers\CampaignReader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Describes a Campaign.
 *
 * @author DESCOTILS Juliette
 * @author GATTACIECCA Bastien
 */
class Campaign extends Model
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
        'activated',
        'target',
        'type',
        'name',
        'description',
        'abbreviate_instructions_words',
        'abbreviate_instructions_global',
        'detailed_instructions_URL',
    ];

    //*********************
    //***    TABLES     ***
    //*********************

    /**
     * Creates the campaigns table.
     */
    public static function createTable(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            /**
             * Primary key.
             */
            $table->primary('id');
            /**
             * Keys.
             */
            $table->string('id');
            $table->boolean('activated');
            $table->string('target');
            $table->string('type');
            $table->string('name');
            $table->string('description');
            $table->string('abbreviate_instructions_words');
            $table->string('abbreviate_instructions_global');
            $table->string('detailed_instructions_URL');
        });
    }

    /**
     * Calls the parser to get the content from the campaign file.
     *
     * @return array The array containing the data.
     */
    public static function getContentFromFile(): array
    {
        return CampaignReader::parseFile(Settings::$RESSOURCES_PATH . Settings::$CAMPAIGN_FILENAME);
    }

    /**
     * Fills the campaigns table.
     *
     * @param array $content The array containing the data to fill up the table.
     */
    public static function fillTable(array $content): void
    {
        DB::table('campaigns')->insert([
            'id' => $content['id'],
            'activated' => $content['activated'],
            'target' => $content['target'],
            'type' => $content['type'],
            'name' => $content['name'],
            'description' => $content['description'],
            'abbreviate_instructions_words' => $content['abbreviate_instructions_words'],
            'abbreviate_instructions_global' => $content['abbreviate_instructions_global'],
            'detailed_instructions_URL' => $content['detailed_instructions_URL'],
        ]);
    }

    //*********************
    //***    GETTERS    ***
    //*********************

    /**
     * Get the only Campaign record.
     *
     * @return Campaign The only Campaign in the campaigns table.
     */
    public static function getInstance(): Campaign
    {
        return self::first();
    }

    /**
     * @return int The id of the Campaign.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool true if the Campaign is activated, false otherwise.
     */
    public function isActivated(): bool
    {
        return $this->activated;
    }

    /**
     * @return String The Campaign's target.
     */
    public function getTarget(): String
    {
        return $this->target;
    }

    /**
     * @return String The Campaign's type.
     */
    public function getType(): String
    {
        return $this->type;
    }

    /**
     * @return String The Campaign's name.
     */
    public function getName(): String
    {
        return $this->name;
    }

    /**
     * @return String The Campaign's description.
     */
    public function getDescription(): String
    {
        return $this->description;
    }

    /**
     * @return String The Campaign's abbreviate instructions for
     * annotating the words.
     */
    public function getAbbreviateInstructionsWords(): String
    {
        return $this->abbreviate_instructions_words;
    }

    /**
     * @return String The Campaign's abbreviate instructions for
     * annotating the document/snippet in its globality.
     */
    public function getAbbreviateInstructionsGlobal(): String
    {
        return $this->abbreviate_instructions_global;
    }

    /**
     * @return String The Campaign's URL to redirect to the detailed
     * instructions.
     */
    public function getDetailedInstructions(): String
    {
        return $this->detailed_instructions_URL;
    }
}
