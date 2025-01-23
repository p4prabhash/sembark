<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class MemberController extends Controller
{
    public function members(Request $request)
    {
        $user_id = auth()->id(); 
        $teamMembers = DB::table('teams')
            ->where('user_id', $user_id)
            ->pluck('id'); 
           
        $members = DB::table('users')
            ->whereIn('team_id', $teamMembers)
            ->paginate(10); 

            
    
        return view('members.index', compact('members'));
    }

    public function createinvitemember(Request $request){
        return view('members.create');
    }
    public function sendInvitemember(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user_id = auth()->id(); 
    
        $teamMember = DB::table('teams')
        ->where('user_id', $user_id)
        ->first(); 
    
        $teamId = $teamMember ? $teamMember->id : null; 
    

        $token = Str::random(32);

        Invitation::create([
            'name'  => $request->name,
            'email' => $request->email,
            'token' => $token,
            'expires_at' => now()->addMinutes(30),
            'team_id'   =>$teamId,
        ]);

        // Send Email
        Mail::to($request->email)->send(new \App\Mail\InviteEmail($token));

        return redirect()
        ->route('members')
        ->with('success', 'Invitation sent successfully!');
    }
    
}
