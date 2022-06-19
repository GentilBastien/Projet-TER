<?php

namespace App\Models\Readers;

use DOMDocument;
use Exception;

class GlobalTagReader extends FileReader
{

    /**
     * Parses the global assessment tags file.
     * @param String $filePATH the path of the xml file
     * @return array containing the value and the name of each global assessment tag.
     */
    public static function parseFile(String $filePATH)
    {
        if (!self::fileExists($filePATH))
            throw new Exception("Global tag file doesn't exist here : " . $filePATH);

        $dom = new DOMDocument();
        if (!$dom->load($filePATH))
            throw new Exception("Impossible to load global tags file from" . $filePATH);

        $tagsList = $dom->getElementsByTagName("tag_global");

        $global_tags = [];
        for ($i = 0; $i < count($tagsList); $i++) {
            $global_tags[$i] = [
                'value' => $tagsList[$i]->getAttribute('value'),
                'tag_name' => $tagsList[$i]->nodeValue,
                'color' => $tagsList[$i]->getAttribute('color'),
            ];
        }

        return $global_tags;
    }
}
