@extends('layouts.main-layout')
@section('content')

<div class="text-center">
    <a class="btn btn-secondary mb-1" href="/users" onclick="showMessage()">Refresh</a>
</div>
<div id="messageDiv"  class="text-center text-success"></div>
<table class="table table-striped table-responsive">
    <tbody>
@foreach ($users as $usr)

    
    <tr >
        <td>
            <b>{{ $usr->name }}  </b>{{ $usr->company }}</td>
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
   
<script>
    function showMessage() {
        var messageDiv = document.getElementById("messageDiv");
        messageDiv.innerHTML = "Page is refreshed!";
    }
</script>









@endsection