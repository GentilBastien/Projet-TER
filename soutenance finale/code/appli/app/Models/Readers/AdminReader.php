<?php
namespace App\Models\Readers;

use DOMDocument;
use Exception;

class AdminReader extends FileReader
{

    /**
     * Parses the admins file.
     * @param String $filePATH the path of the xml file
     * @return array containing the admin's id and the admin's password
     */
    public static function parseFile(String $filePATH)
    {
        if (!self::fileExists($filePATH))
            throw new Exception("Admin file doesn't exist here : " . $filePATH);

        $dom = new DOMDocument();
        if (!$dom->load($filePATH))
            throw new Exception("Impossible to load admin file from" . $filePATH);

        $adminsList = $dom->getElementsByTagName("admin");

        $admins = [];
        for ($i = 0; $i < count($adminsList); $i++) {
            $admins[$i]['id'] = $adminsList[$i]->getAttribute('id');
            $admins[$i]['password'] = $adminsList[$i]->getAttribute('password');
        }
        return $admins;
    }
}
