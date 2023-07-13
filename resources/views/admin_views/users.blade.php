@extends('layouts.main-layout')
@section('content')

<div class="text-center">
    <a class="btn btn-secondary mb-1" href="/users">Refresh</a>
</div>

<table class="table table-striped table-responsive">
    <tbody>
@foreach ($users as $usr)

    
    <tr>
        <td>
            <b>{{ $usr->name }}</b></td>
        <td>{{ $usr->email }}</td>
       <td></td>
    </tr>
    <tr>
        <td>  Last Login:</td>
        <td>{{ $usr->last_login }}</td>
        <td><a href="/user/{{$usr->id }}/edit"><i class="bi bi-pencil-square"></i></a></td>
       
    </tr>

    @endforeach
</tbody>
</table>
   











@endsection