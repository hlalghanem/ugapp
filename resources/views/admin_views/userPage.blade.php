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
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<form method="POST" action="{{ route('user.update',$user->id) }}">
    @csrf
    @method('PUT')
   <div>
   <label for="name" class="form-label">Name</label>
   <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }} "required minlength="4">
   </div>
   <div>
    <label for="company" class="form-label">company</label>
    <input type="text" name="company" id="company" class="form-control" value="{{ $user->company }}" required minlength="4">
    </div>
    <div class="form-group">
        <label for="group">Group:</label>
        <select name="group" id="group" class="form-control">
           
                <option value="1" @if ($user->group_id ===1) selected @endif>
                  User 
                </option>
                <option value="3" @if ($user->group_id ===3) selected @endif>
                    Omega Staff 
                </option>
                <option value="2" @if ($user->group_id ===2) selected @endif>
                    Admin
                </option>
        </select>
    </div>
    <div class="form-group">
        <label for="lang">lang:</label>
        <select name="lang" id="lang" class="form-control">
           
                <option value="en" @if ($user->lang ==="en") selected @endif>
                  EN 
                </option>
                <option value="ar" @if ($user->lang ==="ar") selected @endif>
                    AR
                </option>
                
        </select>
    </div>
    <div class="form-check">
        <input class="form-check-input p-2" name="is_admin" type="checkbox" id="is_admin" {{ ($user->is_admin==1 ?  ' checked' : '') }}>
        <label class="form-check-label" for="is_admin">
            is_admin (can delete today sales)
        </label>
      </div>
    <div>
        <div class="form-check">
            <input class="form-check-input p-2" name="is_active" type="checkbox" id="is_active" {{ ($user->is_active==1 ?  ' checked' : '') }}>
            <label class="form-check-label" for="is_active">
                Active
            </label>
          </div>
        <div>
        <label for="email" class="form-label">Email</label>
        <label   class="form-control">{{ $user->email }} </label>
     </div>
     <div>
        <label for="last_login" class="form-label">last_login</label>
        <label   class="form-control">{{ $user->last_login }} </label>
     </div>
     <div>
        <label for="created_at" class="form-label">created_at</label>
        <label   class="form-control">{{ $user->created_at }} </label>
     </div>
     <button type="submit" class="btn btn-outline-warning m-2">Update User</button>

</form>

<table class="table">

    <tbody>

        @foreach($userBranches as $branch)
        <tr>

            <td>{{ $branch->name }}</td>
            <td>{{ $branch->name_ar }}</td>
            <td>
                <form method="POST" action="{{ route('delete.branchUser', ['user_id' => $user->id, 'branch_id' => $branch->id]) }}">
                    @csrf
                    @method('delete')

                    <button type="submit" title="delete" class="btn btn-outline-danger btn-sm "><i class="bi bi-trash3-fill"></i></button>


                </form>
            </td>
             </tr>
        @endforeach
    </tbody>
</table>

@endsection