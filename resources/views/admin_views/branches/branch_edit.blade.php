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
    <div>
        <label class="form-label" for="sync_interval">sync_interval:</label>
        <input type="number" class="form-control" name="sync_interval" id="sync_interval" value="{{$branch->sync_interval}}" >
    </div>
    <br>
    <div class="form-check">
        <input class="form-check-input p-2" name="is_active" type="checkbox" id="is_active" {{ ($branch->is_active==1 ?  ' checked' : '') }}>
        <label class="form-check-label" for="is_active">
            Active
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input p-2" name="send_payments" type="checkbox" id="send_payments" {{ ($branch->send_payments==1 ?  ' checked' : '') }}>
        <label class="form-check-label" for="send_payments">
            Send Payments
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input p-2" name="send_sales_details" type="checkbox" id="send_sales_details" {{ ($branch->send_sales_details==1 ?  ' checked' : '') }}>
        <label class="form-check-label" for="send_sales_details">
            send_sales_details
        </label>
      </div>
    {{-- <div>
        <label class="form-label" for="is_active">is_active</label>
        <input type="checkbox" name="is_active" id="is_active" {{ ($branch->is_active==1 ?  ' checked' : '') }}>


    </div> --}}
    <button type="submit" class="btn btn-outline-warning m-2">Update Branch</button>
</form>

<hr><br><br>
<h2>Branch Users</h2>
<form method="POST" action="{{ route('assign.branchUser') }}">
    @csrf
    <div>
        <label class="form-label" for="user_id">Select User:</label>
        <select class="btn btn-outline-secondary m-2" name="user_id" id="user_id">
            @foreach ($users_not_assigned as $nuser)
            <option value="{{ $nuser->id }}">{{ $nuser->name }}</option>
            @endforeach
        </select>

        <input type="hidden" name="branch_id" value="{{$branch->id }}">
{{-- 
        <label class="form-label" for="branch_id">Select Branch:</label>
        <select class="btn btn-outline-secondary m-2" name="branch_id" id="branch_id">
            @foreach ($branches as $branch)
            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
            @endforeach
        </select> --}}

        <button type="submit" class="btn btn-outline-info m-2">Assign user</button>

    </div>
</form>
<table class="table">

    <tbody>

        @foreach($users as $user)
        <tr>

            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <form method="POST" action="{{ route('delete.branchUser', ['user_id' => $user->id, 'branch_id' => $user->branch_id]) }}">
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