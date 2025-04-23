<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);
    
        $user->update($request->only('name', 'email'));
    
        return back()->with('success', 'Profil został zaktualizowany.');
    }
    

    /**
     * Delete the user's account.
     */
    public function destroy()
    {
        $user = Auth::user();
        $user->delete();
    
        Auth::logout();
    
        return redirect('/')->with('success', 'Konto zostało usunięte.');
    }
    
}
