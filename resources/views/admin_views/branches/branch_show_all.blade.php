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

<h2>All Branches</h2>
<a href="/branch/new" class="btn btn-outline-info m-2">New Branch</a>

<div class="text-box-container">
    <input type="text" class="rounded-input" id="searchInput" placeholder="Search...">
</div>


<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>
                <a href="?sort=name">Name</a>
            </th>
            
            <th>
                <a href="?sort=last_sync">Last Sync</a>
            </th>
            <th>
                <a href="?sort=last_transaction">Last trans</a>
            </th>
            <th>

            </th>
        </tr>
    </thead>
    <tbody>
        <?php $counter = 1; ?>
        @foreach($branches as $branch)
        <tr>
            <td>{{ $counter++ }}</td>
            <td>{{ $branch->name }}</td>
           
            <td>
                {{\Carbon\Carbon::parse($branch->last_sync)->format('d-M H:i') }}
                </td>
                <td>
                    {{\Carbon\Carbon::parse($branch->last_transaction)->format('d-M H:i') }}
                    </td>
            <td>
            @if ($branch->is_active===1)
                <i class="bi bi-check-circle-fill text-success" title="Active"></i>
                @else
                <i class="bi bi-x-circle-fill text-danger" title="inActive"></i>
                @endif
                @if (strtotime($branch->last_sync) < strtotime('-30 minutes')) 
               
                <i class="bi bi-dash-circle-fill  text-danger" title="offline"></i>
                @else 
                <i class="bi bi-circle-fill text-success" title="online"></i>
                @endif 
                <a href="/branch/{{$branch->id }}/edit"><i class="bi bi-pencil-square"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

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