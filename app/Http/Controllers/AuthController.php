<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;

use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
    
        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function index(){
        return view('dashboard');
    }


    public function showSignupForm(Request $request)
    {
        $token = $request->query('token');
        $invitation = Invitation::where('token', $token)->first();

        if (!$invitation || $invitation->expires_at < now() || $invitation->is_signed_up) {
            return redirect('/')->withErrors('Invalid or expired invitation link.');
        }
        $showRoleDropdown = $invitation->team_id !== null;

        return view('auth.signup', compact('token', 'invitation', 'showRoleDropdown'));
    }


    // 
    public function signup(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
        'token' => 'required|exists:invitations,token',
       
    ]);

    $invitation = Invitation::where('token', $request->token)->first();

    if ($invitation->expires_at < now() || $invitation->is_signed_up) {
        return redirect('/')->withErrors('Invalid or expired invitation link.');
    }

     if ($invitation->team_id) {
        $team = Team::find($invitation->team_id);
    } else {
        $team = Team::create([
            'name' => $validated['name'],  
        ]);
    }
   
    $role = $request->role ?? 'admin';
    // Create the administrator user
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'team_id' => $team->id, 
        'role'   => $role,
    ]);
    $team->update(['user_id' => $user->id]);
     
     $invitation->update(['is_signed_up' => true]);

     // Log the user in
     auth()->login($user);

    return redirect()->route('dashboard')->with('success', 'Signup successful, team created!');
}
}
