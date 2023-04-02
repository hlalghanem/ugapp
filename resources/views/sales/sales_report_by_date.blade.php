@extends('layouts.main-layout')
@section('content')
<div class="container mt-5">
  <div class="row">
    <div class="col-md-6">
      <form method="GET" action="{{ route('reports.bydate') }}">
        <div class="mb-3">
          <label for="branch" class="form-label">Select branch:</label>
          <select class="form-control" id="branch" name="branch">
            <option value="all" {{ ($branch ?? '') == 'all' ? 'selected' : '' }}>All Branches</option>
            @foreach($branches as $branchOption)
            <option value="{{ $branchOption->id }}" {{ ($branch ?? '') == $branchOption->id ? 'selected' : '' }}>{{ $branchOption->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label for="start_date" class="form-label">Start Date</label>

          @php
          $firstDateOfMonth = now()->startOfMonth();;
          @endphp
          <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $start_date ?? $firstDateOfMonth->format('Y-m-d')  }}">
        </div>
        <div class="mb-3">
          <label for="end_date" class="form-label">End Date</label>
          <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $end_date ?? date('Y-m-d') }}">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>

  </div>
</div>


<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>

        <th scope="col">Payment</th>
        <th scope="col">Total</th>
      </tr>
    </thead>
    <tbody>
      @php
      $grand_total = 0; // Initialize the total amount variable
      @endphp
      @foreach ($totals as $total)
      <tr>
        <td>{{ $total->payment_type }}</td>
        <td>KD {{ $total->total_amount }}</td>
        @php
    $grand_total += $total->total_amount; // Add the current item's total_amount to the total variable
    @endphp
      </tr>
      @endforeach
    </tbody>
    <tfoot>
        <tr>
          <td><b>Total</b></td>
          <td><b>KD  {{ number_format($grand_total, 3) }}</b></td>
        </tr>

  </table>

</div>

@endsection