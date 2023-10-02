@extends('layouts.main-layout')
@section('content')
<h2>Statistics</h2>
<hr>
<h4>Payments</h4>
<div class="table-responsive">
<table class="table" >
    <thead>
        <tr>
            <th>Branch</th>
            <th>Count</th>
        </tr>
    </thead>
    @foreach ($paymentsPerBranch as $payments)
        <tr>
            <td>{{ $payments->cust_name }}</td>
            <td>{{ $payments->count }}</td>
        </tr>
    @endforeach
</table>
</div> 
<br>
<h4>Sales Details</h4>
<div class="table-responsive">
<table class="table" >
    <thead>
        <tr>
            <th>Branch</th>
            <th>Count</th>
        </tr>
    </thead>
    @foreach ($sales as $sales)
        <tr>
            <td>{{ $sales->cust_name }}</td>
            <td>{{ $sales->count }}</td>
        </tr>
    @endforeach
</table>
</div> 
<br>
<h4>Voids&Refunds</h4>
<div class="table-responsive">
<table class="table" >
    <thead>
        <tr>
            <th>Branch</th>
            <th>Count</th>
        </tr>
    </thead>
    @foreach ($voidrefund as $vr)
        <tr>
            <td>{{ $vr->cust_name }}</td>
            <td>{{ $vr->count }}</td>
        </tr>
    @endforeach
</table>
</div> 
<br>
<hr>
<h5>Today's Payemnts</h5>
<div class="table-responsive">
<table class="table" >
    <thead>
        <tr>
            <th>Branch</th>
            <th>Count</th>
        </tr>
    </thead>
    @foreach ($todaypayemnts as $tp)
        <tr>
            <td>{{ $tp->cust_name }}</td>
            <td>{{ $tp->count }}</td>
        </tr>
    @endforeach
</table>
</div> 
<br>
<h5>Today's Sales Details</h5>
<div class="table-responsive">
<table class="table" >
    <thead>
        <tr>
            <th>Branch</th>
            <th>Count</th>
        </tr>
    </thead>
    @foreach ($todaysales as $ts)
        <tr>
            <td>{{ $ts->cust_name }}</td>
            <td>{{ $ts->count }}</td>
        </tr>
    @endforeach
</table>
</div> 
<br>
@endsection