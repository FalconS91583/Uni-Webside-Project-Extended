@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Edytuj Komentarz</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('comments.update', $comment) }}">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="content" class="form-label">Treść komentarza</label>
            <textarea name="content" id="content" class="form-control" rows="5" required>{{ old('content', $comment->content) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
    </form>
</div>
@endsection
