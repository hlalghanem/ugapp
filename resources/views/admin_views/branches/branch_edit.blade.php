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



<form method="POST" action="{{ route('branches.update', $branch->id) }}">
    @csrf
    @method('PUT')
    <div>
        <label class="form-label" for="name">Name:</label>
        <input type="text" class="form-control" name="name" id="name" value="{{ $branch->name}}" required minlength="3">
    </div>
    <div>
        <label class="form-label" for="omega_id">Omega ID:</label>
        <input type="number" class="form-control" name="omega_id" id="omega_id" value="{{$branch->omega_id}}" >
    </div>
    <br>
    <div class="form-check">
        <input class="form-check-input p-2" name="is_active" type="checkbox" id="is_active" {{ ($branch->is_active==1 ?  ' checked' : '') }}>
        <label class="form-check-label" for="is_active">
            Active
        </label>
      </div>
    {{-- <div>
        <label class="form-label" for="is_active">is_active</label>
        <input type="checkbox" name="is_active" id="is_active" {{ ($branch->is_active==1 ?  ' checked' : '') }}>


    </div> --}}
    <button type="submit" class="btn btn-outline-warning m-2">Update Branch</button>
</form>




@endsection