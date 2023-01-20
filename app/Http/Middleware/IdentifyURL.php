<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IdentifyURL
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
        //check slug and segment string match
        // if($request->segment(1) != "") {
        //     // $currentURL = $request->url();
        //     // $slugUrl = explode(".", $currentURL);
        //     // $slug = explode("//", $slugUrl[0])[1];
        //     // if($slug."-login" != $request->segment(1)){
        //     //     abort(404, 'Our system do not recognize this domain. Please contact your manager.');
        //     // }
        // }
       
        return $next($request);
    }
}
