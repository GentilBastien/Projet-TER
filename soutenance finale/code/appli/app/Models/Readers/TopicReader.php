<?php
namespace App\Models\Readers;

use DOMDocument;
use Exception;

class TopicReader extends FileReader
{
    protected $topics = array();

    /**
     * Parses the topic file.
     * @param String $filePATH the path of the xml file
     * @return array containing the topic's id, the keywords, conversationnals and explanations related to each topic.
     */
    public static function parseFile(String $filePATH)
    {
        if (!self::fileExists($filePATH))
            throw new Exception("Snippet file doesn't exist here : " . $filePATH);

        $dom = new DOMDocument();
        if (!$dom->load($filePATH))
            throw new Exception("Impossible to load topic file from" . $filePATH);

        $topicsList = $dom->getElementsByTagName("topic");
        $keywordsList = $dom->getElementsByTagName("keyword");
        $conversationalsList = $dom->getElementsByTagName("conversational");
        $explanationsList = $dom->getElementsByTagName("explanation");

        for ($i = 0; $i < count($topicsList); $i++) {
            $keyword_value = $keywordsList[$i]->nodeValue;
            $conversational_value = $conversationalsList[$i]->nodeValue;
            $explanation_value = $explanationsList[$i]->nodeValue;

            $topics[$i] = [
                'id' => $topicsList[$i]->getAttribute('number'),
                'keywords' => strlen($keyword_value) > 0 ? $keyword_value : '(no keywords)',
                'conversational' => strlen($conversational_value) > 0 ? $conversational_value : '(no conversational)',
                'explanation' => strlen($explanation_value) > 0 ? $explanation_value : '(no explanation)',
            ];
        }

        return $topics;
    }
}
