<?php

namespace App\Models;

use App\Models\Readers\AdminReader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Admin extends Model
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

    //*********************
    //***    TABLES     ***
    //*********************

    /**
     * Creates the admins table.
     *
     * @return array The array containing the data.
     */
    public static function createTable(): void
    {
        Schema::create('admins', function (Blueprint $table) {
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
     * Calls the parser to get the content from the admins file.
     *
     * @return array The array containing the data.
     */
    public static function getContentFromFile(): array
    {
        return AdminReader::parseFile(Settings::$RESSOURCES_PATH . Settings::$ADMIN_FILENAME);
    }

    /**
     * Fills the admins table.
     *
     * @param array $content The array containing the data to fill up the table.
     */
    public static function fillTable(array $content): void
    {
        foreach ($content as $key => $value) {
            self::insert([
                'id' => $value['id'],
                'password' => $value['password']
            ]);
        }
    }
}
