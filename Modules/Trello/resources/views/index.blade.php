@extends('trello::layouts.master')

@section('content')
    <div class="container-md mt-5">
        <h1>Module: {!! config('trello.name') !!}</h1>

        <h3>Features:</h3>

        <h5 class="mt-4">Import Card</h5>
        <form action="{{ route('trello.import') }}" method="POST" enctype="multipart/form-data"
              class="bg-light p-4 border rounded">
            @csrf
            <div class="form-group">
                <label for="file">Choose file:</label>
                <input type="file" name="file" id="file" class="form-control-file">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
