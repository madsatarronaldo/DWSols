<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use App\Models\User;
use App\Models\CompaniesData;
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
                            return redirect('dashboard');
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
        $Data = CompaniesData::all();
        $cro = [];
        foreach($Data as $dat)
        {
            if(!in_array($dat->cro , $cro))
            {
                array_push($cro , $dat->cro);
            }
        }
        $data = CompaniesData::paginate(15);
        return view('dashboard' , compact(['data' , 'cro']));
    }
    /*----------------------------------------------------------------------------------------------*/
    /*----------------------------------------------------------------------------------------------*/
    /*----------------------------------------------------------------------------------------------*/
    public function UserFilter(Request $request)
    {
        if( $request->startdate == NULL || $request->enddate==NULL)
        {
            return redirect('/dashboard')->with('Fail' , 'Dates Required');
        }
        $data = CompaniesData::where('name','LIKE',"%{$request->company_name}%")->where(['status'=>$request->status , 
               'cro'=>$request->cro])->whereBetween('reg_date' , [$request->startdate , $request->enddate])->paginate(15);
        $cro = [];
        $Data = CompaniesData::all();
        foreach($Data as $dat)
        {
            if(!in_array($dat->cro , $cro))
            {
                array_push($cro , $dat->cro);
            }
        }
        return view('dashboard' , compact(['data' , 'cro']));
    }
}

