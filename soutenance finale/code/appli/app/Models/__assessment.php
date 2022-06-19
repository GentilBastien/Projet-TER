<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;

class __assessment
{
    private $_globalAnnotation;
    private $_wordAnnotations;
    private $_assignation_id;
    private $_type;

    public function __construct(int $assignation_id, $data, String $type)
    {
        $this->_assignation_id = $assignation_id;
        $this->_type = $type;
        $this->_globalAnnotation = null;
        $this->_wordAnnotations = null;

        foreach ($data as $key => $value) {
            /**
             * Global annotation
             */
            if ($key == 'global')
                $this->_globalAnnotation = $value;

            /**
             * Words annotation
             */
            if ($key == 'words') {
                foreach ($value as $k => $v)
                    $this->_wordAnnotations[$k] = $v;
                if ($this->_wordAnnotations == null)
                    $this->_wordAnnotations = [];
            }
        }
    }

    /**
     * Check if this assessment is ready to be saved and pushed to the database.
     * This assessment may not be ready for such an action, it must validate one of
     * these points :
     * - globalwords -> global annotation must be positive (at least)
     * - global -> global annotation must be positive (at least)
     * - words -> word annotation array must have one element (at least)
     *
     * @return bool true if this assessment is able to be saved and pushed, false otherwise.
     */
    public function isValid(): bool
    {
        if (str_contains($this->_type, 'global')) {
            return $this->_globalAnnotation >= 0;
        } else if ($this->_type == 'words') {
            return count($this->_wordAnnotations) >= 0;
        } else
            return false;
    }

    /**
     * Updates the DB with the annotated word/global assessments.
     *
     * @return bool true if the DB has been updated successfully, false otherwise.
     */
    public function updateDB(): bool
    {
        /**
         * Update the global assessment.
         */
        GlobalAssessment::find($this->_assignation_id)
            ->update(
                ['annotation' => $this->_globalAnnotation]
            );

        /**
         * Set the NOT_REVIEWED -2 code to -1 (unassessed).
         * In fact, it increments by 1 all records. And then, we set
         * the annotated words.
         */
        DB::table('words_assessments')
            ->where('assignation_id', $this->_assignation_id)
            ->increment('annotation');

        /**
         * Update all the word assessments (primary key of WordAssessment is assignation_id/indice).
         */
        foreach ($this->_wordAnnotations as $indice => $annotation) {
            WordAssessment::where('assignation_id', $this->_assignation_id)
                ->where('indice', $indice)
                ->update(
                    ['annotation' => $annotation]
                );
        }

        /**
         * Update the assignation status at 'completed'.
         */
        Assignation::find($this->_assignation_id)
            ->update(
                ['is_completed' => true]
            );

        return true;
    }
}
