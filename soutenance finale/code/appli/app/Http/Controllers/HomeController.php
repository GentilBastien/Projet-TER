<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Campaign;
use App\Models\Expert;
use App\Models\GlobalTag;
use App\Models\Topic;
use App\Models\WordTag;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;


/**
 * This class has methods to display views and share data with them.
 * The application has 4 different views : a welcome, a login, a
 * dashboard and an assessment view.
 */
class HomeController extends Controller
{
    /**
     * @return View The welcome view which is about to choose who the user
     * wants to register as.
     */
    public function welcome(): View
    {
        return view('auth.welcome');
    }

    /**
     * Depending on the user's type, the login method redirect to the good
     * connection web page since experts and admins don't have the same login
     * web page.
     *
     * @param String $type The user's type. Must be either "expert" or "admin"
     * @return View|RedirectResponse The correct login view depending on the
     * user's type or redirecting if type is not "expert" or "admin".
     */
    public function login(String $type): View|RedirectResponse
    {
        if ($type != "expert" && $type != "admin")
            return redirect()->route('welcome');
        return view('auth.login', ['type' => $type]);
    }

    /**
     * Dashboard function redirect to the correct dashboard (expert/admin).
     *
     * @return View The dashboard view.
     */
    public function dashboard(): View
    {
        $type = session('type');
        if ($type == "expert")
            return $this->dashboardExpert($type);
        return $this->dashboardAdmin($type);
    }


    /**
     * @param String $type 'expert'.
     * @return View The expert's dashboard view.
     */
    public function dashboardExpert(String $type): View
    {
        $user = session('user');
        $expert_topics_id = $user->getTopicsId();

        $topicsAdvancement = [];
        foreach ($expert_topics_id as $topic_id)
            array_push($topicsAdvancement, Topic::find($topic_id)->getAdvancementOf($user));

        $has_next = !$user->hasFinished();

        return view(
            'dashboard.expert',
            [
                'token' => session('token'),
                'user' => $user,
                'type' => $type,
                'has_next' => $has_next,
                'campaign' => Campaign::getInstance(),
                'topicsAdvancement' => json_encode($topicsAdvancement),
            ]
        );
    }

    /**
     * @param String $type 'admin'.
     * @return View The admin's dashboard view.
     */
    public function dashboardAdmin(String $type): View
    {

        $user = session('user');

        $experts = Expert::all();
        $topics = Topic::all();

        $expertsAdvancement = [];
        foreach ($experts as $expert)
            array_push($expertsAdvancement, $expert->getAdvancement());
        $topicsAdvancement = [];
        foreach ($topics as $topic)
            array_push($topicsAdvancement, $topic->getAdvancement());

        return view(
            'dashboard.admin',
            [
                'token' => session('token'),
                'user' => $user,
                'type' => $type,
                'campaign' => Campaign::getInstance(),
                'expertsAdvancement' => json_encode($expertsAdvancement),
                'topicsAdvancement' => json_encode($topicsAdvancement),
            ]
        );
    }

    /**
     * @return View The assessment view allows the experts to assess a
     * snippet/document. It needs an assignation because it gives all
     * the data needed to display an assessment (the expert, the topic,
     * the target (assessment type), and the assessment (document/snippet)).
     *
     * @return RedirectResponse to the login page if the token is not valid.
     * 
     * Important note : There is no check about the fact we can call this method
     * although there is no more assessment for this Expert. It is actually not
     * possible to call this method with no remaining assessment because there is
     * only 2 ways to display an assessment :
     * - from the dashboard, where the "start assessment" button is disabled if no
     * assessment left
     * - from the assessment view by clicking "save and next" button which is disabled
     * as well if no assessment left.
     */
    public function assessment(): View|RedirectResponse
    {
        if(!(session()->has('token'))) {
            return redirect()->route('welcome');
        }

        $expert = session('user'); //must be expert since he goes to an assessment.
        $_a = $expert->getNextAssignation(); //Check the next assignation of the expert
       
        if (!$_a)
            throw new Exception("There is no more assessment to display. This code should not be executed.");

        $has_next = !$expert->isLastAssignation(); //Check if this is the last one. False if there is only one remaining.
        $assignation_id = $_a->id;
        $topic = Topic::find($_a->topic_id); //get the topic
        $campaign = Campaign::getInstance(); //get the Campaign instance
        $assessment = Assessment::assessmentOf($_a); //get the assessment linked to the assignation (snippet/document)
        $globaltags = GlobalTag::getGlobalTags(); //get the globalTags
        $wordtags = WordTag::getWordTags(); //get the wordTags
        $writeglobaltags = GlobalTag::writeGlobalTags(); //write the globaltags js code
        $writewordtags = WordTag::writeWordTags(); //write the wordtags js code

        return view(
            'assessment.assessment',
            [
                'token' => session('token'),
                'user' => $expert,
                'type' => session('type'),
                'has_next' => $has_next,
                'assignation_id' => $assignation_id,
                'topic' => $topic,
                'campaign' => $campaign,
                'assessment' => $assessment,
                'globaltags' => $globaltags,
                'wordtags' => $wordtags,
                'writeglobaltags' => $writeglobaltags,
                'writewordtags' => $writewordtags,
            ]
        );
    }
}
