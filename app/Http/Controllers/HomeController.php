<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $notice=DB::table('noticeboards')
        ->join('users', 'noticeboards.user_id','=','users.id')
        ->select('noticeboards.subject', 'noticeboards.description', 'noticeboards.time','users.user_type','users.name')
        ->get();
        
        return view('dash', [
            'title' => 'Dashboard',
            'notices'=>$notice
        ]);
    }

    public function profile()
    {
        $user = Auth::user();
        $currentcontactnumber=$user->contactnumber;
        $log = DB::table('activity_log')->select('description','subject_id', 'subject_type', 'causer_type','properties','created_at','updated_at')->orderBy('created_at', 'desc')->get();
        // ->whereRaw(DB::Raw('Date(created_at)=CURDATE()'))
        return view('profile', [
            'title' => $user->name,
            'activity' => $log,
            'currentno'=>$currentcontactnumber,
            'crntmail'=>$user->email,
            'education'=>$user->education,
            'location'=>$user->location,
            'skills'=>$user->skills,
            'notes'=>$user->notes
            ]);
    }

    public function setLocale($lan)
    {
        \Session::put('locale', $lan);
        return redirect()->back();
    }

}
