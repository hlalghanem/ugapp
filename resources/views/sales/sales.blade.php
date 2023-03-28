@extends('layouts.main-layout')
@section('content')

{{-- @if (is_null($branchIds))
<p>No active branches found for this user.</p>
@else

<p> Branches#{{ $branchCount }}</p> 
<p> IDs #{{ $branchIds }}</p>  --}}

{{-- 

<hr>
<table>
    <thead>
        <tr>
            <th>Customer Name</th>
            <th>Total Amount Paid</th>
            <th>Last End-of-Day Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>
                    <a href="/sales/{{ $transaction->omega_id }}">{{ $transaction->cust_name }}</a>
                    </td>
                <td>{{ $transaction->{'SUM(amount_paid)'} }}</td>
                <td>{{ $transaction->{'MAX(eod_date)'} }}</td>
            </tr>
        @endforeach
    </tbody>
</table> --}}

{{-- @endif --}}




{{-- this working fine --}}
<br>
@foreach ($transactions as $transaction)
<div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-6">
         <b> <a class="text-decoration-none text-dark" href="/sales/{{ $transaction->omega_id }}">{{ strtoupper($transaction->cust_name) }} </a></b>
        </div>
        <div class="col-2">
          
            <td>
                @if (date('Y-m-d', strtotime($transaction->eod_date)) != date('Y-m-d'))
                     <span class="badge text-bg-warning">
                {{\Carbon\Carbon::parse($transaction->eod_date)->format('D') }}
            </span>
                @else
                     <span class="badge text-bg-info">
                {{\Carbon\Carbon::parse($transaction->eod_date)->format('D') }}
            </span>
                @endif
              
               
        </td>
           </div>
        <div class="col-4 ">
            KD<b> {{ $transaction->{'SUM(amount_paid)'} }}</b>
        </div>
      </div>
    </div>
    {{-- <div class="card-body">
       
    </div>  --}}
</div>
<br>

  @endforeach


@endsection