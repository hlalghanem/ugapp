@extends('layouts.main-layout')
@section('content')

<div class="container">
    <br>
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
                <td>{{ $total->total_amount }}</td>
              </tr>
       @endforeach
          </tbody>
          <tfoot>
            <tr>
                <td><b>Total KWD</b></td>
                <td><b>{{ $brTodayTotal->total_amount }}</b></td>
              </tr>
          </tfoot>
    </table>
  </div>
<br>
<h3><span class="badge text-bg-warning">Last 3 days</span></h3>
  <div class="table-responsive">
    <table class="table">
        
          <tbody>
            @foreach ($totalsbyDate as $total)
            <tr>
              <td> {{ \Carbon\Carbon::parse($total->eod_date)->format('D j M') }} </td>
             
                <td>{{ $total->total_amount }}</td>
              </tr>
       @endforeach
          </tbody>
         
    </table>
  </div>

  </div>

  @endsection