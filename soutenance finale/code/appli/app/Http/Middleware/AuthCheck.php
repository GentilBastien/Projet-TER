<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * If a user is not connected and tries to go somewhere that is not the login
         * page, he is redirected to it.
         */
        if (!session()->has('token') && !str_ends_with($request->path(), "login")) {
            return redirect()->route('welcome')->with('fail', 'You must be logged in!');
        }

        /**
         * If a user is connected and tries to launch an other instance of his session, then he is brought back
         * to his working page.
         */
        if (session()->has('token') && str_ends_with($request->path(), "login")) {
            return redirect()->route('dashboard');
        }

        //return $next($request);
        /**
         * Prevent the user from using the browser back button.
         */
        return $next($request)->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
