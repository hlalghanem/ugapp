@extends('layouts.main-layout')
@section('content')
<style>
   .refresh-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 10px 20px;
  font-size: 16px;
  /* font-weight:100; */
  color:black;
  background-color:#c0c0c0;
  border:grey;
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
  border: 5px solid #fff;
  border-top-color: transparent;
  animation: spinner 0.8s linear infinite;
}

@keyframes spinner {
  to {
    transform: rotate(360deg);
  }
}


</style>


<div class="text-center m-1">
<!-- <a href="/live" class="btn btn-outline-secondary"> <i class="bi bi-arrow-clockwise"></i></a> -->
<button class="refresh-btn">
  <span class="spinner"></span>
  <i class="bi bi-arrow-clockwise"></i>
  Refresh
</button>
</div>

@foreach ($transactions as $transaction)
<div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-6">
         <b> <a class="text-decoration-none text-dark" href="/sales/{{ $transaction->omega_id }}">{{ strtoupper($transaction->name ) }} </a></b>
        </div>
        <div class="col-2">
          
            <td>
                <!-- {{$transaction->last_eod}} -->
                @if (date('Y-m-d', strtotime($transaction->last_eod)) != date('Y-m-d'))
                     <span class="badge text-bg-warning">
                {{\Carbon\Carbon::parse($transaction->last_eod)->format('D') }}
            </span>
                @else
                     <span class="badge text-bg-info">
                {{\Carbon\Carbon::parse($transaction->last_eod)->format('D') }}
            </span>
                @endif
              
               
        </td>
           </div>
        <div class="col-4 ">
            KD<b> <a class="text-decoration-none text-dark" href="/sales/{{ $transaction->omega_id }}"> {{ $transaction->total_paid }}</a></b>
        </div>
      </div>
    </div>
   
</div>
<br>

  @endforeach
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