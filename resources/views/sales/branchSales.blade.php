@extends('layouts.main-layout')
@section('content')

<div class="container">
  <br>
 
  <h2>{{ $branchinfo->name }}</h2>
  <p>L.Sync. {{ \Carbon\Carbon::parse($branchinfo->last_sync)->format('jM H:i ')}}
    L.Tran. {{ \Carbon\Carbon::parse($branchinfo->last_transaction)->format('jM H:i')}}</p>

  <h3><span class="badge text-bg-info"> {{ \Carbon\Carbon::parse($branchinfo->last_eod)->format('D j M') }} </span>
  </h3>
  @if(count($totals)>0)
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
  @else
  <p class="m-2">No Transactions!</p>
  @endif

  @if(count($totalsbyDate)>0)
  <h3><span class="badge text-bg-warning">Last 5 days</span></h3>
  <div class="table-responsive">
    <table class="table">

      <tbody>
        @foreach ($totalsbyDate as $total)
        <tr>
          <td>
            <a href="/sales/{{ request('omega_id') }}/{{ $total->eod_date }}">{{ \Carbon\Carbon::parse($total->eod_date)->format('D j M') }}</a>
          </td>


          <td>KD {{ $total->total_amount }}</td>
        </tr>
        @endforeach
      </tbody>

    </table>
    @endif
    @if(count($prevSales)>0)

    <label for="date">Select a date:</label>
    <select id="date" name="date" class="form-select">
      @foreach ($prevSales as $date)
      <option value="{{ $date->eod_date }}" class="form-option">{{ \Carbon\Carbon::parse($date->eod_date)->format('D j M y') }}</option>
      @endforeach
    </select>
    <button onclick="goToDate()" class="btn btn-outline-secondary">Go</button>
    <script>
      function goToDate() {
        var dateSelect = document.getElementById("date");
        var selectedDate = dateSelect.options[dateSelect.selectedIndex].value;
        window.location.href = "/sales/{{ request('omega_id') }}/" + selectedDate;
      }
    </script>

    <style>
      .form-select {
        padding: 0.5rem;
        border-radius: 0.25rem;
        border: 1px solid #ced4da;
        font-size: 1rem;
        line-height: 1.5;
        width: 100%;
        max-width: 20rem;
        background-color: #fff;
        background-clip: padding-box;
      }

      .form-option {
        font-size: 1rem;
      }

      .btn {
        margin-top: 0.5rem;
        font-size: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
      }
    </style>

    @endif
    <br>
    <!-- Go Back Button  -->
    <button class="btn btn-outline-secondary" onclick="goBack()">Go Back </button>
    <script>
      function goBack() {
        window.history.back();
      }
    </script>
    <!-- End ---Go Back Button  -->
    <!-- <a href="/" class="btn btn-outline-secondary"> <i class="bi bi-house-door">Back</i></a> -->
    
  </div>
  <br>
</div>

@endsection