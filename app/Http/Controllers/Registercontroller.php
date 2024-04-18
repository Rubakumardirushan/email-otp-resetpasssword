<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\SendOTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
class Registercontroller extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
       
        $email=$request->input('email');
        $otp = mt_rand(100000, 999999);
        $user->temp_otp=$otp;
        $user->save();
        Mail::to($email)->send(new SendOTPMail($otp));

        return view('Customer.Otp')->with('email',$email);
      
        
}


public function verifyOTP(Request $request)
{
    $email = $request->input('email');
    $otp = $request->input('otp');
$user=User::where('email',$email)->where('temp_otp',$otp)->first();

    if ($otp == $user->temp_otp) {
       
    
        $user->status='active';
        $user->email_verified_at = Carbon::now('Asia/Colombo');
        $user->temp_otp=null;
        $user->save();
        Auth::login($user);
        return redirect('/home');
    }else{
        return view('Customer.Otp')->with('email',$email)->with('$error','invalid otp');
    }




}
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
       $user=User::where('email',$request->email)->where('status','active')->first();
       if($user){
        Auth::login($user);
        return redirect('/home');
       }
       else{
        $email = $request->input('email');
        return view('Customer.Otp')->with('email',$email);

       }

        
    }
    else{
        return redirect('/login')->with('error','invaild password or email');
    }

   
}



public function resend(Request $request){
    $email = $request->input('email');
    $user=User::where('email',$email)->first();
    $otp = mt_rand(100000, 999999);
    $user->temp_otp=$otp;
    $user->save();
    Mail::to($email)->send(new SendOTPMail($otp));

        return view('Customer.Otp')->with('email',$email);

}

public function emailOTP(Request $request){
    $email = $request->input('email');
    $user=User::where('email',$email)->first();
    $otp = mt_rand(100000, 999999);
    $user->temp_otp=$otp;
    $user->save();
    Mail::to($email)->send(new SendOTPMail($otp));
   if($user){
    return view('Customer.OtpEmail')->with('email',$email);

   }else{
    return back()->with('error','email not found');
   }




}
public function verifyEmailOTP(Request $request)
{
    $email = $request->input('email');
    $otp = $request->input('otp');
$user=User::where('email',$email)->where('temp_otp',$otp)->first();
    
        if ($otp == $user->temp_otp) {
            
            $user->temp_otp=null;
            $user->email_verified_at = Carbon::now('Asia/Colombo');
        
            $user->save();
            return view('Customer.Newpassword')->with('email',$email)->with('success','email verified successfully');

          
        }else{
            return view('Customer.OtpEmail')->with('email',$email)->with('$error','invalid otp');
        }






}
public function newPassword(Request $request)
{
    $email = $request->input('email');
    $password = $request->input('password');
    $user=User::where('email',$email)->first();
    $user->password=bcrypt($password);
    $user->save();
    Auth::login($user);
    return redirect('/home');}






}