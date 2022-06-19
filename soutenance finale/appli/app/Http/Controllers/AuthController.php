<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Expert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Controller managing authentication and redirections.
 */
class AuthController extends Controller
{

    /**
     * Logs out by removing the current token in the browser if there
     * is any.
     *
     * @return RedirectResponse The redirection.
     */
    public function logout(): RedirectResponse
    {
        if (session()->has('token')) {
            session()->pull('token');
        }
        return redirect()->route('welcome');
    }

    /**
     * Checks if the data given in the incoming request allow the user to
     * be redirected to his dashboard. If it is the case, a token is put in his browser
     * with some other informations such as a user variable and a type variable.
     *
     * @param String $type The user's type (admin or expert).
     * @param Request The request from the login form.
     * @return RedirectResponse The redirection.
     */
    public function check(Request $request, String $type): RedirectResponse
    {
        /**
         * Validate the input.
         * User login and password are both required. Password must be between 5 and 16 chars.
         */
        $request->validate([
            'user_login' => 'required',
            'user_password' => 'required'
        ]);

        /**
         * Check the user type to search in the good table.
         */
        if ($type == "expert")
            $user = Expert::find($request->user_login);
        if ($type == "admin")
            $user = Admin::find($request->user_login);

        //check the id
        if (isset($user)) {
            //check the password
            if ($request->user_password == $user->password) {
                /**
                 * correct credentials, allow the user to get his own session :
                 * -> create a new token
                 * -> create a new session containing this token and the user's type
                 * -> redirect to the dashboard with this current Session.
                 */
                $token = Str::random(30);
                $request->session()->put('token', $token);
                $request->session()->put('user', $user);
                $request->session()->put('type', $type);
                return redirect()->route('dashboard');
            } else {
                /**
                 * back() method is used to redirect to the same previous uri with an error message.
                 */
                return back()->with('fail', 'Incorrect password.');
            }
        } else {
            return back()->with('fail', 'Incorrect id.');
        }
    }
}
