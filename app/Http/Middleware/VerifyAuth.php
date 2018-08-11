<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Input;
use Closure;
use Illuminate\Support\Facades\Redirect;
 

class VerifyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //dd($request->client_id);
       // $header_Array = apache_request_headers();
//       \Illuminate\Support\Facades\Crypt::decrypt("login_token");
//       \Illuminate\Support\Facades\Crypt::decrypt($client_id);
//        if($header_Array['login_token'] != session('login_token'))
//        {
//            return redirect("request");
//        }
        
        return $next($request);
      
    }
}
