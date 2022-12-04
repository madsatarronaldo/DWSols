<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\Models\User;
use App\Models\VerifyUser;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Mail;
use Goutte\Client;
use Illuminate\Support\Facades\Auth;
// use Symfony\Component\HttpClient\HttpClient;
use Illuminate\Support\Facades\Http;
class AuthenticationController extends Controller
{
    public function UserRegisteration(Request $request)
    {
        $request->validate([
            'username'=>'required|unique:users|min:5',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:5',
            'confirm_password'=>'required|min:5',
           ]);
            $pass = $request->password;
            $con_pass = $request->confirm_password;
            if ($con_pass != $pass )
            {
                return redirect('/registeration')->with('Fail' , 'Passwords Must Be Same');
            }
           $data = $request->all();
           $user = $this->create($data);
           if($user)
           {
               $token = Str::random(64);
               VerifyUser::create([
                   'user_id' => $user->id, 
                   'token' => $token
               ]);
               Mail::send('VerificationEmail', ['token' => $token], 
               function($message) use($request)
               {
                   $message->to($request->email);
                   $message->subject('User Verification Mail');
               });
               return redirect('/')->with('Success' , 'Please Verify Your Email');
           }
           else
           {
                User::where(['users'=>$request->username])->latest()->delete();
               return back()->with('Fail' , 'Account Not Created');
           }

    }
    public function create(array $data)
    {
        return User::create([
        'username' => $data['username'],
        'email' => $data['email'],
        'password'=>Crypt::encrypt($data['password']),
        'created_at' => Carbon::now()->toDateTimeString(),
      ]);
    }
    public function verifyAccount($token)
    {
        
        $verifyUser = VerifyUser::where('token', $token)->first();
        if(!is_null($verifyUser) )
        {
            $user = $verifyUser->user;
            if(!$user->is_email_verified)  
            {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->email_verified_at = Carbon::now()->toDateTimeString();
                $verifyUser->user->save();
                return redirect('/')->with('Success' ,'Email Verified. Login Now');
            } 
            else 
            {
                return redirect('/')->with('Success' ,'Email Already Verified. Login Now');
            }
        }
        else
        {
            return redirect('/')->with('Fail' ,'Email Could Not Be Verified');
        }
  
    }

    /*----------------------------------------------------------------------------------------------*/
    /*----------------------------------------------------------------------------------------------*/
    /*----------------------------------------------------------------------------------------------*/
    public function UserLogin(Request $request)
    {
       $user = User::where([
        'username' => $request->username,
        ])->first();
        if (!$user)
        {
            return back()->with('Fail' , 'Incorrect Username');
        }
        else
        {
            if($request->email === $user->email)
            {
                if($request->password === (Crypt::decrypt($user->password)))
                {
                    if($user->is_email_verified === 1 || $user->is_email_verified == true )
                    {
                        $request->Session()->put('userid' , $user->id); 
                        $request->Session()->put('useremail' , $user->email);
                        if(Session::has(['userid' , 'useremail']))
                        {
                            return view('dashboard');
                        }
                        else
                        {
                            Session::forget([['userid' , 'useremail']]);
                            return back()->with('Fail' , 'Something Went Wrong');
                        }
                    }
                    else
                    {
                        return redirect('/')->with('Fail' , 'Verfiy Email First');
                    }
                }
                else
                {
                    return back()->with('Fail' , 'Incorrect Password');
                }
            }
            else
            {
                return back()->with('Fail' , 'Incorrect Email');
            }
        }
    }
    /*----------------------------------------------------------------------------------------------*/
    /*----------------------------------------------------------------------------------------------*/
    /*----------------------------------------------------------------------------------------------*/
    public function UserDashboard()
    {
        dd("here");
        return view('dashboard');
        // $client = new Client();
        // $url = 'https://www.worldometers.info/coronavirus/';
        // $page = $client->request('GET', $url);
        // echo "<pre>";
        // print_r($page);
        // $response = Http::asForm()->post('https://eservices.secp.gov.pk/eServices/NameSearch.jsp', [
        //     'searchOption' => 'Including Exact String',
        //     'searchName' => 'air',
        // ]);
        // dd($response->body());

        // $client = new Client();
        // $res = $client->request('POST', 'https://eservices.secp.gov.pk/eServices/NameSearch.jsp', [
        //     'form_params' => [
        //         'searchOption' => 'Including Exact String',
        //         'searchName' => 'air',
        //     ]
        // ]);
        // echo $res->getStatusCode();
        // 200
        // dd($res->status());
        // 'application/json; charset=utf8'
        // echo $res->getBody();
        
    }
}

// Route::get('/registeration', function () {
//     return view('register');
// });
