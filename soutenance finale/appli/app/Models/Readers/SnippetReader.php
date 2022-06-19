<?php
namespace App\Models\Readers;

use DOMDocument;
use Exception;

class SnippetReader extends FileReader
{
    /**
     * Parses the snippet file.
     * @param String $filePATH the path of the xml file
     * @return array containing the id of the related document, the topic id,
     * the snippet's title and the snippet's abstract.
     */
    public static function parseFile(String $filePATH)
    {
        if (!self::fileExists($filePATH))
            throw new Exception("Snippet file doesn't exist here : " . $filePATH);

        $dom = new DOMDocument();
        if (!$dom->load($filePATH))
            throw new Exception("Impossible to load snippet file from" . $filePATH);

        $snippetList = $dom->getElementsByTagName("snippet");
        $titleList = $dom->getElementsByTagName("title");
        $abstractList = $dom->getElementsByTagName("abstract");

        $snippets = [];
        for ($i = 0; $i < count($snippetList); $i++) {
            $title_value = $titleList[$i]->nodeValue;
            $abstract_value = $abstractList[$i]->nodeValue;

            $snippets[$i] = [
                'topic_id' => $snippetList[$i]->getAttribute('topic_id'),
                'document_id' => $snippetList[$i]->getAttribute('document_id'),
                'title' => strlen($title_value) > 0 ? $title_value : '(no title)',
                'abstract' => strlen($abstract_value) > 0 ? $abstract_value : '(no abstract)',
            ];
        }

        return $snippets;
    }
}
