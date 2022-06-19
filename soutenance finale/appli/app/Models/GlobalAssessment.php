<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GlobalAssessment extends Model
{
    use HasFactory;
    protected $table = 'global_assessments';
    protected $primaryKey = 'assignation_id';
    public $timestamps = false;

    /**
     * Overriden attribute for mass assignement.
     *
     * @access protected
     * @var String[]
     */
    protected $fillable = [
        'assignation_id',
        'annotation',
    ];

    //*********************
    //***    TABLES     ***
    //*********************

    /**
     * Creates the global assessments table.
     */
    public static function createTable(): void
    {
        Schema::create('global_assessments', function (Blueprint $table) {
            /**
             * Primary key.
             */
            $table->primary('assignation_id');
            /**
             * Keys.
             */
            $table->bigInteger('assignation_id');
            $table->integer('annotation');
            /**
             * Foreign keys.
             */
            $table->foreign('assignation_id')->references('id')->on('assignations');
            $table->foreign('annotation')->references('value')->on('global_tags');
        });
    }

    /**
     * Fills the global_assessments table with default values expecting future updates.
     */
    public static function fillTable(int $assignation_id): void
    {
        $default_global_tag = GlobalTag::min('value');
        GlobalAssessment::insert([
            'assignation_id' => $assignation_id,
            'annotation' => $default_global_tag,
        ]);
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
        return $this->belongsTo(GlobalTag::class, 'annotation');
    }
}
