<?php

namespace App\Http\Controllers;

use App\Models\__assessment;
use App\Models\Campaign;
use App\Models\Writers\LogWriter;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;


class AssessController extends Controller
{
    /**
     * This method controller handles a save request.
     * 
     * @return RedirectResonse The redirection.
     */
    public function save(Request $request, int $assignation_id, String $route_name): RedirectResponse
    {
        if(!(session()->has('token'))) {
            return redirect()->route('welcome');
        }

        $data = json_decode($request->data); //= {'words':{}, 'global':0}
        /**
         * if json_decode could not decode the request data
         */
        if (!isset($data)) {
            LogWriter::addLog("Assessment is unfinished because data could not be set.");
            return back()->with('fail', 'Assessment is unfinished because data could not be set.');
        }

        $save_request = new __assessment($assignation_id, $data, Campaign::getInstance()->getType());

        /**
         * checks if the assessment is valid according to the campaign type/target
         */
        if ($save_request->isValid()) {
            $save_request->updateDB();
            return redirect()->route($route_name);
        }else {
            LogWriter::addLog("Assessment is unfinished because it is invalid.");
            return back()->with('fail', 'Assessment is unfinished because it is invalid.');
        }
    }
}
