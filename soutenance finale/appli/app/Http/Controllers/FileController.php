<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Assignation;
use App\Models\Campaign;
use App\Models\Expert;
use App\Models\Readers\AssignationReader;
use App\Models\Readers\ExpertReader;
use App\Models\Readers\FileReader;
use App\Models\Settings;
use App\Models\Writers\LogWriter;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use ZipArchive;

/**
 * FileController is the controller that writes files on serv and
 * download them in local. This class also reads local files to
 * add new records in the DB.
 */
class FileController extends Controller
{
    /**
     * Requests the Assessment model to write the file(s) of
     * completed assessments and to download this/those files
     * in user's computer local.
     *
     * @return RedirectResponse Writes a file.
     */
    public function export($content_type): RedirectResponse
    {
        $redirect = redirect()->route('dashboard');
        $file_path = Assessment::exportAssessments($content_type);
        /**
         * If $file_path contains more than one file, we put all of
         * them together in a zip file, which is exported. Note: PHP
         * don't handle several files export.
         */
        if (is_array($file_path)) {
            $this->downloadZipFile($file_path, true);
        } else {
            $this->downloadFile($file_path);
        }
        return $redirect;
    }

    public function add(Request $request, String $add_type): RedirectResponse
    {
        if ($add_type != "experts" && $add_type != "assessments")
            throw new Exception("Invalid add_type argument => " . $add_type);

        if($add_type == "experts") {
            $content = ExpertReader::parseFileFromContent($request->content_expert);
            Expert::fillTable($content);
        }else{
            // $add_type == "assessments"
            $content = AssignationReader::parseFileFromContent($request->content_assessment);
            Assignation::fillTable($content);
        }
        return redirect()->route('dashboard');
    }

    /**
     * Download a server file in local.
     *
     * @param String $file_path The path of the file we want to
     * download.
     * @return void Downloads operation.
     */
    private function downloadFile(String $file_path)
    {
        header("Content-type: application/download");
        header("Content-Disposition: attachment; filename=" . basename($file_path));
        header("Pragma: public");
        header("Content-Transfer-Encoding: binary");
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        flush();
    }

    /**
     * Take several files in parameter to put them in a ZIP file and
     * downloads it on the computer in local.
     *
     * @param array $file_paths An array containing all the paths.
     * @return void Downloads a ZIP archive file.
     */
    private function downloadZipFile(array $file_paths)
    {
        /**
         * Creates the ZIP archive files.
         */
        date_default_timezone_set(Settings::$DEFAULT_LOCALE);
        $date = date(Settings::$DEFAULT_DATETIME_FORMAT, time());
        $campaign_target = Campaign::getInstance()->getTarget();
        $zipname = $date . '_' . $campaign_target . '_' . 'completed_assessments.zip';
        $zip = new ZipArchive;
        $zip->open($zipname, ZipArchive::CREATE);
        foreach ($file_paths as $file_path) {
            $zip->addFile($file_path, basename($file_path));
        }
        $zip->close();
        /**
         * Downloads the ZIp file in local.
         */
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . $zipname);
        header("Pragma: public");
        header("Content-Transfer-Encoding: binary");
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
        flush();
    }
}
