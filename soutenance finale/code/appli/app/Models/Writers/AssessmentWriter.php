<?php

namespace App\Models\Writers;

use App\Models\Assignation;
use App\Models\GlobalAssessment;
use App\Models\Settings;
use App\Models\WordAssessment;
use Exception;

/**
 * Using AssessmentWriter to write completed and remaining assessments in txt files.
 */
class AssessmentWriter
{
    /**
     * Write an Assessment File.
     *
     * @param String $content_type The content_type of the Assessment file (if it is remaining or
     * completed files). It can be either "completed" or "remaining".
     * @param String $target snippets|documents
     * @param String $type global|words. It can't be "globalwords" : two calls must be done to export
     * both global and words assessments. If content_type is remaining, we don't need this param.
     * @return String The full path
     */
    public static function writeFile(String $content_type, String $target, String $type): String
    {
        /**
         * Check illegal arguments
         */
        if ($content_type != "completed" && $content_type != "remaining")
            throw new Exception("Invalid content_type argument => " . $content_type);
        if ($target != "snippets" && $target != "documents")
            throw new Exception("Invalid target argument => " . $target);
        if ($content_type == "completed" && $type != "global" && $type != "words")
            throw new Exception("Invalid type argument for completed assessments => " . $type);
        /**
         * Format defaults.
         */
        date_default_timezone_set(Settings::$DEFAULT_LOCALE);
        $date = date(Settings::$DEFAULT_DATETIME_FORMAT, time());
        /**
         * Write filename
         */
        $is_remaining = $content_type == "remaining";
        $file_name = $is_remaining ?
            $date . "_remaining_" . $target . "_assessments.txt" :
            $date . "_" . $type . "_" . $target . "_" . $content_type . "_assessments.txt";

        $file_path = Settings::$EXPORT_PATH . $file_name;
        /**
         * Open in writemode only, create file if doesn't exist yet, target the start of the file (rewrite).
         */
        $file = fopen($file_path, 'w');
        if (!$file)
            throw new Exception("Could not open file.");

        $assignations = $is_remaining ?
            Assignation::getRemainingAssessments() :
            Assignation::getCompletedAssessments();
        $res = "";
        /**
         * When we are writting remaining files, we can only write the three info
         * concerning an uncompleted assignation : (topic_id/document_id/expert_id).
         */
        if ($is_remaining) {
            foreach ($assignations as $assignation) {
                $res = $res .
                    $assignation->topic_id . " " .
                    $assignation->document_id . " " .
                    $assignation->expert_id . " " .
                    PHP_EOL;
            }
        } else {
            // $content_type == "completed"
            /**
             * When an assignation has a completed state, we only put the global annotation
             * for the global file. We put a list of (word / index / word_annotation) for the
             * words file.
             */
            if ($type == 'global') {
                foreach ($assignations as $assignation) {
                    $res = $res .
                        $assignation->topic_id . " " .
                        $assignation->document_id . " " .
                        $assignation->expert_id . " " .
                        GlobalAssessment::find($assignation->id)->annotation . PHP_EOL;
                }
            } else {
                // $type == 'words'
                foreach ($assignations as $assignation) {
                    $res = $res .
                        $assignation->topic_id . " " .
                        $assignation->document_id . " " .
                        $assignation->expert_id . " ";
                    foreach (WordAssessment::getWordsAssessment($assignation->id) as $word_assessment) {
                        if ($word_assessment->annotation >= 0)
                            $res = $res . $word_assessment->word . " " . $word_assessment->indice . " " . $word_assessment->annotation . " ";
                    }
                    $res = $res . PHP_EOL;
                }
            }
        }

        fwrite($file, $res);
        fclose($file);
        return $file_path;
    }
}
