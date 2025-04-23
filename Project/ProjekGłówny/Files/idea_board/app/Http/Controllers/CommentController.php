<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Idea;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Idea $idea)
    {
        $request->validate([
            'content' => 'required',
        ]);

        Comment::create([
            'content' => $request->content,
            'idea_id' => $idea->id,
        ]);

        return back()->with('success', 'Komentarz został dodany.');
    }
    public function edit(Comment $comment)
{
    $this->authorize('update', $comment); // Sprawdzenie uprawnień
    return view('comments.edit', compact('comment'));
}
public function update(Request $request, Comment $comment)
{
    $this->authorize('update', $comment); // Sprawdzenie uprawnień

    $request->validate([
        'content' => 'required',
    ]);

    $comment->update([
        'content' => $request->content,
    ]);

    return redirect()->route('ideas.index')->with('success', 'Komentarz został zaktualizowany.');
}
public function destroy(Comment $comment)
{
    $this->authorize('delete', $comment); // Sprawdzenie uprawnień
    $comment->delete();

    return back()->with('success', 'Komentarz został usunięty.');
}


}

