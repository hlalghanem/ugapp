@extends('layouts.main-layout')
@section('content')
<br>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



<form method="POST" action="{{ route('branches.store') }}">
    @csrf
    <div>
        <label class="form-label" for="name">Name:</label>
        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required minlength="3">
    </div>
    <div>
        <label class="form-label" for="name_ar">Arabic Name:</label>
        <input type="text" class="form-control" name="name_ar" id="name_ar" value="{{ old('name_ar') }}" required minlength="3">
    </div>
    <div>
        <label class="form-label" for="omega_id">Omega ID:</label>
        <input type="number" class="form-control" name="omega_id" id="omega_id" value="{{ old('omega_id') }}" required min="100000" max="999999">
    </div>
    <button type="submit" class="btn btn-outline-info m-2">Create Branch</button>
</form>




@endsection