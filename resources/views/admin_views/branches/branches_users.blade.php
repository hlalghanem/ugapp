@extends('layouts.main-layout')
@section('content')

<style>
    .text-box-container {
        float: right;
    }

    .rounded-input {
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        background-color: #F5F5F5;
        outline: none;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    }
</style>
<br>
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<h2>All Branches & Users</h2>


<div class="text-box-container">
    <input type="text" class="rounded-input" id="searchInput" placeholder="Search...">
</div>
<br>
<form method="POST" action="{{ route('assign.branchUser') }}">
    @csrf
    <div>
        <label class="form-label" for="user_id">Select User:</label>
        <select class="btn btn-outline-secondary m-2" name="user_id" id="user_id">
            @foreach ($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>

        <label class="form-label" for="branch_id">Select Branch:</label>
        <select class="btn btn-outline-secondary m-2" name="branch_id" id="branch_id">
            @foreach ($branches as $branch)
            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-outline-info m-2">Assign user</button>

    </div>
</form>

<br>
<table class="table">

    <tbody>

        @foreach($branches_users as $branch)
        <tr>

            <td>{{ $branch->branch_name }}</td>
            <td>{{ $branch->branch_omega_id }}</td>
            <td><b>{{ $branch->name }}</b> <i> {{ $branch->email }}</i></td>
            <td>
                <form method="POST" action="{{ route('delete.branchUser', ['user_id' => $branch->id, 'branch_id' => $branch->branch_id]) }}">
                    @csrf
                    @method('delete')

                    <button type="submit" title="delete" class="btn btn-outline-danger btn-sm "><i class="bi bi-trash3-fill"></i></button>


                </form>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

<script>
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('table tbody tr');

    searchInput.addEventListener('input', function() {
        const searchTerm = searchInput.value.toLowerCase();

        tableRows.forEach(function(row) {
            const cells = row.querySelectorAll('td');
            let matches = false;

            cells.forEach(function(cell) {
                const text = cell.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    matches = true;
                }
            });

            if (matches) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection