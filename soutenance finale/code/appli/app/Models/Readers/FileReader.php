<?php
namespace App\Models\Readers;

use Exception;

/**
 * Base class for reading files to set up the Campaign.
 */
abstract class FileReader
{
    /**
     * Indicates if a file path is valid or not. If it is, checks if the structure
     * of the file is correct as well.
     *
     * @param $filePATH The file's path.
     * @return bool true if the file is valid and respects its schema, false otherwise.
     */
    public static function fileExists(String $filePATH): bool
    {
        return is_file($filePATH);
    }

    /**
     * Reads a txt file. In order :
     * - tries to open the stream with the file ;
     * - if it fails, throw an exception ;
     * - yields every lines of the file ;
     * - closes the stream to prevent the server from using useless ressources.
     * @param String The path of the file.
     * @return Generator A php generator.
     */
    public static function readTxt(String $filePATH)
    {
        /**
         * Opens the stream.
         */
        $source = fopen($filePATH, "r");

        if (!$source)
            throw new Exception("Impossible to load txt file at " . $filePATH);
        /**
         * Generates lines with yield.
         */
        while (($line = fgets($source)) !== false) {
            yield $line;
        }
        /**
         * Closes the stream.
         */
        fclose($source);
    }

    /**
     * Undocumented function
     *
     * @param String $content
     * @return array
     */
    public static function readTxtFromContent(String $content)
    {
        return explode(PHP_EOL, $content);
    }

    /**
     * Parses a file to read its content.
     *
     * @param String $filePATH The file's path.
     */
    public abstract static function parseFile(String $filePATH);
}
