<?php

namespace App\Models\Readers;

use Exception;

class AssignationReader extends FileReader
{
    protected $assignations = array();

    /**
     * Parses the assignation file.
     * @param String $filePATH the path of the txt file
     * @return array containing the expert's id, the topic's id and the document's id
     */
    public static function parseFile(String $filePATH)
    {
        if (!self::fileExists($filePATH))
            throw new Exception("Assignation file doesn't exist here : " . $filePATH);

        $data = self::readTxt($filePATH);
        $assignations = [];
        $i = 0;

        foreach ($data as $line) {
            if (strlen($line) <= 2)
                throw new Exception("Error in assignation file line " . $i . " in " . $filePATH);

            $elements = explode(' ', $line);
            if (count($elements) != 3)
                throw new Exception("Assignation file at " . $filePATH . "could not find 3 elements at line" . $line);

            $assignations[$i] = [
                'expert_id' => $elements[0],
                'topic_id' => $elements[1],
                'document_id' => $elements[2],
            ];
            $i++;
        }
        return $assignations;
    }

    /**
     * Parses a file with its content in one litteral string.
     *
     * @param String $content The file's content.
     * @return array An array containing the expert's id, the topic's id and the document's id.
     */
    public static function parseFileFromContent(String $content)
    {
        $data = self::readTxtFromContent($content);
        $assignations = [];
        $i = 0;

        foreach ($data as $line) {
            if (strlen($line) <= 2)
                throw new Exception("Error in assignation file line " . $i . " in content: " . $content);

            $elements = explode(' ', $line);
            if (count($elements) != 3)
                throw new Exception("3 elements are expected at line " . $i);

            $assignations[$i] = [
                'expert_id' => $elements[0],
                'topic_id' => $elements[1],
                'document_id' => $elements[2],
            ];
            $i++;
        }
        return $assignations;
    }
}
