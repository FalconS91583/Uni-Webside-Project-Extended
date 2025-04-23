<?php
namespace App\Http\Controllers;

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Idea;

class IdeaController extends Controller
{
    public function index()
    {
        $ideas = Idea::with('user')->latest()->get();
        return view('ideas.index', compact('ideas'));
    }

    public function create()
    {
        return view('ideas.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);
    
        Idea::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);
    
        return redirect()->route('ideas.index');
    }
    
}
