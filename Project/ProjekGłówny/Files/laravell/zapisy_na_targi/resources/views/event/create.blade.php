@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Zapis na Targi</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('event.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="event_name" class="form-label">Nazwa Targów</label>
            <input type="text" class="form-control @error('event_name') is-invalid @enderror" name="event_name" id="event_name" value="{{ old('event_name') }}" required>
            @error('event_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="details" class="form-label">Szczegóły</label>
            <textarea class="form-control @error('details') is-invalid @enderror" name="details" id="details" rows="5" required>{{ old('details') }}</textarea>
            @error('details')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Zapisz się</button>
    </form>
</div>
@endsection
