@extends('layouts.main-layout')
@section('content')



{{-- Updated --}}




{{-- this working fine --}}
<div class="text-center m-1">
<a href="/" class="btn btn-outline-secondary"> <i class="bi bi-arrow-clockwise"></i></a>
</div>

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
            KD<b> <a class="text-decoration-none text-dark" href="/sales/{{ $transaction->omega_id }}"> {{ $transaction->{'SUM(amount_paid)'} }}</a></b>
        </div>
      </div>
    </div>
    {{-- <div class="card-body">
       
    </div>  --}}
</div>
<br>

  @endforeach


@endsection