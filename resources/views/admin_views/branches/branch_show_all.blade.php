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
<a href="/branch/new">create a new branch</a>

<div class="text-box-container">
<input type="text"  class="rounded-input" id="searchInput" placeholder="Search...">
</div>



<table class="table" >
<thead>
        <tr>
            <th>#</th>
            <th>
                <a href="?sort=name">Name</a>
            </th>
            <th>
               Active
            </th>
            <th>
                <a href="?sort=last_sync">Last Sync</a>
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
                @if ($branch->is_active===1)
                <i class="bi bi-check-circle-fill text-success"></i>
                @else
                <i class="bi bi-x-circle-fill text-danger"></i>
                @endif
            </td>
                <td>
                    {{\Carbon\Carbon::parse($branch->last_sync)->format('d-M-y H:i') }}
               {{-- $branch->last_sync     Carbon::parse($branch->last_sync)->addHours(3) --}}
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