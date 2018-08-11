<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Input;
use Closure;
use Illuminate\Support\Facades\Redirect;
use App\WC_Models\Oauth_Client;


class VerifyApiToken
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
        
        if(Input::get('client_id') && Input::get('client_secret'))
        {
            $client = Input::get('client_id');
           $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client);
          $user = Oauth_Client::where('id',$client_id)->where('secret',Input::get('client_secret'))->first();
          if(!empty($user))
          {
             return Redirect::route('token_verify', [$client]);
           //   return redirect('/');
          }
          else
          {
              return redirect('api/token_notVerified');
          }
        //  return redirect("hello");
        }
        return $next($request);
    }
}
