<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller



{

    public function createinvite(Request $request){
        return view('invitations.create');
    }
    public function sendInvite(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $token = Str::random(32);

        Invitation::create([
            'name'  => $request->name,
            'email' => $request->email,
            'token' => $token,
            'expires_at' => now()->addMinutes(30),
        ]);

        // Send Email
        Mail::to($request->email)->send(new \App\Mail\InviteEmail($token));

        return redirect()
        ->route('invited.customers')
        ->with('success', 'Invitation sent successfully!');
    }

    public function viewInvitedCustomers(Request $request)
    {
        $status = $request->input('status'); 
        $query = Invitation::query();

        if ($status !== null) {
            $query->where('is_signed_up', $status);
        }

        $invitations = $query->paginate(10);

        return view('invitations.index', compact('invitations'));
    }
}
