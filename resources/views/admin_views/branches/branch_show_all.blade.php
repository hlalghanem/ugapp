@extends('layouts.main-layout')
@section('content')
<br>
<h2>All Branches</h2>
<a href="/branch/new">create a new branch</a>
<table class="table" >
<thead>
        <tr>
            <th>#</th>
            <th>
                <a href="?sort=name">Name</a>
            </th>
            <th>
                <a href="?sort=is_active">Active</a>
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


@endsection