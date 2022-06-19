<?php
namespace App\Models\Readers;

use DOMDocument;
use Exception;

class WordTagReader extends FileReader
{

    /**
     * Parses the global assessment tags file.
     * @param String $filePATH the path of the xml file
     * @return array containing the value and the name of each global assessment tag.
     */
    public static function parseFile(String $filePATH)
    {
        if (!self::fileExists($filePATH))
            throw new Exception("Words tag file doesn't exist here : " . $filePATH);

        $dom = new DOMDocument();
        if (!$dom->load($filePATH))
            throw new Exception("Impossible to load word tags file from" . $filePATH);

        $tagsList = $dom->getElementsByTagName("tag_word");

        $word_tags = [];
        for ($i = 0; $i < count($tagsList); $i++) {
            $word_tags[$i] = [
                'value' => $tagsList[$i]->getAttribute('value'),
                'tag_name' => $tagsList[$i]->nodeValue,
                'color' => $tagsList[$i]->getAttribute('color'),
            ];
        }

        return $word_tags;
    }
}
