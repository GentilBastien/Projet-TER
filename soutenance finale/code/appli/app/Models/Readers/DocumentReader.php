<?php

namespace App\Models\Readers;

use DOMDocument;
use Exception;

class DocumentReader extends FileReader
{
    /**
     * Parses the document file.
     * @param String $filePATH the path of the xml file
     * @return array containing the document's id and the document's relative path
     */
    public static function parseFile(String $filePATH)
    {
        if (!self::fileExists($filePATH))
            throw new Exception("Document file doesn't exist here : " . $filePATH);

        $dom = new DOMDocument();
        if (!$dom->load($filePATH))
            throw new Exception("Impossible to load document file from" . $filePATH);

        $docList = $dom->getElementsByTagName("document");
        $extUrlList = $dom->getElementsByTagName("external_url");
        $intUriList = $dom->getElementsByTagName("internal_uri");

        $docs = [];
        for ($i = 0; $i < count($docList); $i++) {
            $url_value = $extUrlList[$i]->nodeValue;
            $uri_value = $intUriList[$i]->nodeValue;
            $docs[$i] = [
                'id' => $docList[$i]->getAttribute('id'),
                'external_url' => strlen($url_value) > 0 ? $url_value : null,
                'internal_uri' => strlen($uri_value) > 0 ? $uri_value : null,
            ];
        }
        return $docs;
    }
}
