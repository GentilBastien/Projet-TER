<?php

namespace App\Models\Readers;

use Exception;

class ExpertReader extends FileReader
{
    /**
     * Parses the experts list file.
     * @param String $filePATH the path of the txt file
     * @return array containing the expert's id and the expert's password.
     */
    public static function parseFile(String $filePATH)
    {
        if (!self::fileExists($filePATH))
            throw new Exception("Expert file doesn't exist here : " . $filePATH);

        $data = self::readTxt($filePATH);
        $experts = [];
        $i = 0;

        foreach ($data as $line) {
            if (strlen($line) <= 2)
                throw new Exception("Error in expert file line " . $i . " in " . $filePATH);

            $elements = explode(' ', $line);
            if (count($elements) != 2)
                throw new Exception("Expert file at " . $filePATH . "could not find 2 elements at " . $line);

            $experts[$i] = [
                'id' => $elements[0],
                'password' => $elements[1],
            ];
            $i++;
        }
        return $experts;
    }

    /**
     * Parses a file with its content in one litteral string.
     *
     * @param String $content The file's content.
     * @return array An array containing the expert's id and the expert's password.
     */
    public static function parseFileFromContent(String $content)
    {
        $data = self::readTxtFromContent($content);
        $experts = [];
        $i = 0;

        foreach ($data as $line) {
            if (strlen($line) <= 2)
                throw new Exception("Error in expert file line " . $i . " in content: " . $content);

            $elements = explode(' ', $line);
            if (count($elements) != 2)
                throw new Exception("2 elements are expected at line " . $i);

            $experts[$i] = [
                'id' => $elements[0],
                'password' => $elements[1],
            ];
            $i++;
        }
        return $experts;
    }
}
