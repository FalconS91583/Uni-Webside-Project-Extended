<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Auth;

class EventRegistrationController extends Controller
{
    public function create()
    {
        return view('event.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'details' => 'required|string|max:1000',
        ]);

        EventRegistration::create([
            'user_id' => Auth::id(),
            'event_name' => $request->event_name,
            'details' => $request->details,
        ]);

        return redirect()->route('event.create')->with('success', 'Zapisano na targi!');
    }
}

