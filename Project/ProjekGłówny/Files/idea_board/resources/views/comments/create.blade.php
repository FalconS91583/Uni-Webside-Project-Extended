<form method="POST" action="{{ route('comments.store', $idea->id) }}">
    @csrf
    <textarea name="content" placeholder="Treść komentarza"></textarea>
    <button type="submit">Dodaj komentarz</button>
</form>
