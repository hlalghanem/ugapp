@extends('layouts.main-layout')
@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


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
  .refresh-btn {
display: inline-flex;
align-items: center;
justify-content: center;
padding: 10px 20px;
font-size: 16px;
font-weight: bold;
color: orange;
background-color: whitesmoke;
border: black;
border-radius: 8px;
cursor: pointer;
/* box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23); */
position: relative;
overflow: hidden;
}

.spinner {
position: absolute;
top: 0;
left: 0;
right: 0;
bottom: 0;
background-color: rgba(255, 255, 255, 0.8);
display: none;
z-index: 1;
}

.refresh-btn.loading .spinner {
display: block;
}

.refresh-btn.loading {
pointer-events: none;
}

.refresh-btn.loading span:not(.spinner) {
opacity: 0.9;
}

.spinner::after {
content: '';
display: block;
width: 30px;
height: 30px;
margin: auto;
border-radius: 50%;
border: 5px solid grey;
border-top-color: transparent;
animation: spinner 0.8s linear infinite;
}

@keyframes spinner {
to {
  transform: rotate(360deg);
}
}
</style>

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
          {{-- <td>KD {{ $total->total_amount }}</td> --}}
<td>
  KD{{ $total->total_amount }}
          


</td>


        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td><b>Total</b></td>
          <td>
            

            @php
            $prevTotalPaid = session('total_paid_' . $branchinfo->name, null);
            $currentTotalPaid = $brTodayTotal->total_amount;
            $changed = ($prevTotalPaid !== null && $prevTotalPaid !== $currentTotalPaid);
          
            @endphp
            @if ($changed)
            @php
            $color = ($currentTotalPaid > $prevTotalPaid) ? 'lightgreen' : 'red';
            $arrowColor = ($currentTotalPaid > $prevTotalPaid) ? 'lightgreen' : 'red';
            $arrow = ($currentTotalPaid > $prevTotalPaid) ? '▲' : '▼';
            $changediff =$currentTotalPaid - $prevTotalPaid;
            @endphp
            <b>KD {{ $brTodayTotal->total_amount }}</b>&nbsp;

          <span style="color: {{ $arrowColor }}">{{ $arrow }}KD&nbsp;{{number_format($changediff, 3)}}</span>
            @else
            <b>KD {{ $brTodayTotal->total_amount }}</b>
            @endif
            @php
            session(['total_paid_' . $branchinfo->name =>  $brTodayTotal->total_amount]);
            @endphp

{{-- 

            @php
          $prevTotalPaid = session('total_amount_' . $branchinfo->name, null);
          $currentTotalPaid = $brTodayTotal->total_amount;
          $changed = ($prevTotalPaid !== null && $prevTotalPaid !== $currentTotalPaid);
          @endphp
          @if ($changed)
          @php
          $color = ($currentTotalPaid > $prevTotalPaid) ? 'lightgreen' : 'red';
          $arrowColor = ($currentTotalPaid > $prevTotalPaid) ? 'lightgreen' : 'red';
          $arrow = ($currentTotalPaid > $prevTotalPaid) ? '▲' : '▼';
          $diff =$currentTotalPaid - $prevTotalPaid;
          @endphp
           <b>KD {{ $brTodayTotal->total_amount }}</b>&nbsp;
        <span style="color: {{ $arrowColor }}">KD<b> {{ $diff }}</b>{{ $arrow }}</span>
       
          @else
          <b>KD {{ $brTodayTotal->total_amount }}</b>
          @endif
          @php
          session(['total_amount_' . $branchinfo->name => $total->total_amount]);
          @endphp --}}
            
            
            
            
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
  @else
  <p class="m-2">No Transactions!</p>
  @endif
  <div class="text-center m-1">
    <!-- <a href="/live" class="btn btn-outline-secondary"> <i class="bi bi-arrow-clockwise"></i></a> -->
    <button class="refresh-btn">
      <span class="spinner"></span>
      <i class="bi bi-arrow-clockwise"></i>
      Refresh
    </button>
  </div>
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
    
    
   

    <!-- Go Back Button  -->
   
    
    <!-- End ---Go Back Button  -->
    <!-- <a href="/" class="btn btn-outline-secondary"> <i class="bi bi-house-door">Back</i></a> -->
    
  </div>
  @if(count($prevSales)>0)
  <div class="row">
   
    <div class="col">
    
  <select id="date" name="date" class="form-select">
    @foreach ($prevSales as $date)
    <option value="{{ $date->eod_date }}" class="form-option">{{ \Carbon\Carbon::parse($date->eod_date)->format('D j M y') }}</option>
    @endforeach
  </select>
    </div>
    <div class="col">
      <button onclick="goToDate()" class="btn btn-outline-secondary">Go</button>
    </div>
  </div>

  
  
  
  
  <script>
    function goToDate() {
      var dateSelect = document.getElementById("date");
      var selectedDate = dateSelect.options[dateSelect.selectedIndex].value;
      window.location.href = "/sales/{{ request('omega_id') }}/" + selectedDate;
    }
  </script>
   @endif
   <br>

  
    <div class="text-center m-1"> <button class="btn btn-outline-secondary" onclick="goBack()">Go Back </button>
      <script>
        function goBack() {
          window.history.back();
        }
      </script>
        </div>
   
  <br>

  @auth
    @if (auth()->user()->is_admin == 1)
    <div class="text-center m-1">
      <br>
      <br>
      <br>
      <br>
      <br>
    <form method="POST" action="{{ route('deletetodaytranactions', ['omega_id' => request('omega_id')]) }}" >
      @csrf
      @method('delete')
      <button type="submit" class="btn btn-outline-danger"> Delete today transactions & reupload fresh data</button>
    </form>
  </div>        
    @endif
 @endauth
</div>


<script>
  const refreshBtn = document.querySelector('.refresh-btn');

  refreshBtn.addEventListener('click', () => {
    refreshBtn.classList.add('loading');
    setTimeout(() => {
      location.reload();
    }, 1000);
  });
</script>

@endsection