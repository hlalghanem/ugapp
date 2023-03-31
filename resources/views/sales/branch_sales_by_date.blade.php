@extends('layouts.main-layout')
@section('content')
<br>
<div class="container">
@if (is_null($brTodayTotal))
<div class="text-center">
    no transactions

    <br/>
    <a href="/sales/{{ request('omega_id') }}" class="btn btn-outline-secondary"> Back</a>
</div>
    <p></p>
@else
 
   
  
    <h2>{{ $brTodayTotal->cust_name }}</h2>
    
    <br>
    <h3><span class="badge text-bg-info"> {{ \Carbon\Carbon::parse($brTodayTotal->eod_date)->format('D j M') }} </span> </h3>
   
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
              
              <th scope="col">Payment</th>
              <th scope="col">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($totals as $total)
            <tr>
                <td>{{ $total->payment_type }}</td>
                <td>KD {{ $total->total_amount }}</td>
              </tr>
       @endforeach
          </tbody>
          <tfoot>
            <tr>
                <td><b>Total</b></td>
                <td><b>KD {{ $brTodayTotal->total_amount }}</b></td>
              </tr>
          </tfoot>
    </table>
  </div>
<br>
<a href="/sales/{{ request('omega_id') }}" class="btn btn-outline-secondary"> Back</a>


   
@endif
   
  </div>
  <br><br>

  @endsection