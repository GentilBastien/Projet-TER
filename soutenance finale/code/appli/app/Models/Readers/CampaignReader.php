<?php

namespace App\Models\Readers;

use DOMDocument;
use Exception;

class CampaignReader extends FileReader
{

    /**
     * Parses the campaign file.
     * @param String $filePATH the path of the xml file
     * @return array containing the the informations of the current campaign (campaign id, activation state, type of campaign, name, decription, instructions, link to the detailed instructions).
     */
    public static function parseFile(String $filePATH)
    {
        if (!self::fileExists($filePATH))
            throw new Exception("Campaign file doesn't exist here : " . $filePATH);

        $dom = new DOMDocument();
        if (!$dom->load($filePATH))
            throw new Exception("Impossible to load campaign file from" . $filePATH);

        return [
            'id' => $dom->getElementsByTagName('campaign')[0]->getAttribute('id'),
            'target' => $dom->getElementsByTagName('target')[0]->textContent,
            'activated' => boolval($dom->getElementsByTagName('activated')[0]->textContent),
            'type' => $dom->getElementsByTagName('type')[0]->textContent,
            'name' => $dom->getElementsByTagName('name')[0]->textContent,
            'description' => $dom->getElementsByTagName('description')[0]->textContent,
            'abbreviate_instructions_words' => $dom->getElementsByTagName('abbreviate_instructions_words')[0]->textContent,
            'abbreviate_instructions_global' => $dom->getElementsByTagName('abbreviate_instructions_global')[0]->textContent,
            'detailed_instructions_URL' => $dom->getElementsByTagName('detailed_instructions_URL')[0]->textContent,
        ];
    }
}
