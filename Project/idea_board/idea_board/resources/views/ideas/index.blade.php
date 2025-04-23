@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Tablica Pomysłów</h1>
    <div class="row">
        @foreach($ideas as $idea)
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title">{{ $idea->title }}</h4>
                        <p class="card-text">{{ $idea->description }}</p>
                        <p class="text-muted">
                            Dodane przez: {{ $idea->user->name ?? 'Anonim' }} 
                            <br>
                            Data: {{ $idea->created_at->format('d-m-Y H:i') }}
                        </p>
                        <hr>
                        <h5>Komentarze:</h5>
                        @foreach($idea->comments as $comment)
                        <div class="mb-2">
                        <p>{{ $comment->content }}</p>
                        <p class="text-muted" style="font-size: 0.9em;">
                         Data: {{ $comment->created_at->format('d-m-Y H:i') }}
                        </p>
                        @auth
                        @if (auth()->id() === $idea->user_id) {{-- Tylko właściciel pomysłu --}}
                        <a href="{{ route('comments.edit', $comment) }}" class="btn btn-sm btn-warning">Edytuj</a>
                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" style="display: inline;">
                        @csrf
                         @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Usuń</button>
                        </form>
                    @endif
            @endauth
        </div>
            @endforeach
                        <form method="POST" action="{{ route('comments.store', $idea) }}">
                            @csrf
                            <div class="input-group">
                                <textarea class="form-control" name="content" placeholder="Dodaj komentarz" required></textarea>
                                <button class="btn btn-primary" type="submit">Dodaj</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @auth
        <div class="text-center mt-4">
            <a href="{{ route('ideas.create') }}" class="btn btn-success">Dodaj Nowy Pomysł</a>
        </div>
    @endauth
</div>
@endsection
