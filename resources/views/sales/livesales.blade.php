@extends('layouts.main-layout')
@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@auth
@php
    $user = auth()->user();
    $language = $user->lang;
    app()->setLocale($language);
    $redcolor=0;
   
@endphp
@endauth
<style>
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


<div class="text-center m-1">
  <!-- <a href="/live" class="btn btn-outline-secondary"> <i class="bi bi-arrow-clockwise"></i></a> -->
  <button class="refresh-btn">
    <span class="spinner"></span>
    <i class="bi bi-arrow-clockwise"></i>
    {{ __('translationFile.refresh') }}
  </button>
</div>

@foreach ($transactions as $transaction)
<div class="card">
 
  <div class="card-header">
    <div class="row p-0 m-0">

    @if (strtotime($transaction->last_sync) < strtotime('-30 minutes')) 
    @php
       $redcolor=1;
    @endphp
   
   @else
   @php
       $redcolor=0;
    @endphp
   @endif 
   <div class="col-6 p-0 m-0">
     
        @if($language === 'en')
        <b> <a class="text-decoration-none  @if($redcolor == 1) text-danger @else text-dark @endif" href="/sales/{{ $transaction->omega_id }}">{{ strtoupper($transaction->name ) }} </a></b>
        @else
        <b> <a class="text-decoration-none @if($redcolor == 1) text-danger @else text-dark @endif" href="/sales/{{ $transaction->omega_id }}">{{ strtoupper($transaction->name_ar ) }} </a></b>
        @endif
       
      </div>
      <div class="col-2  p-0 m-0">

        <td>
          <!-- {{$transaction->last_eod}} -->
          {{-- @if (date('Y-m-d', strtotime($transaction->last_eod)) != date('Y-m-d'))
          <span class="badge text-bg-warning">
            {{\Carbon\Carbon::parse($transaction->last_eod)->format('D') }}
          </span>
          @else
          <span class="badge text-bg-info">
            {{\Carbon\Carbon::parse($transaction->last_eod)->format('D') }}
          </span>
          @endif --}}

          @if (date('Y-m-d', strtotime($transaction->last_eod)) != date('Y-m-d'))
         <span class="badge text-bg-warning @if($redcolor == 1) text-danger @else text-dark @endif">  @if($language === 'en')  {{ \Carbon\Carbon::parse($transaction->last_eod)->format('D') }}  @else    {{ \Carbon\Carbon::parse($transaction->last_eod)->isoFormat('ddd') }} @endif</span>
          @else
          <span class="badge text-bg-info @if($redcolor == 1) text-danger @else text-dark @endif">  @if($language === 'en')  {{ \Carbon\Carbon::parse($transaction->last_eod)->format('D') }}  @else    {{ \Carbon\Carbon::parse($transaction->last_eod)->isoFormat('ddd') }} @endif</span>
          @endif


        </td>
      </div>
      <div class="col-4  p-0 m-0">

        @php
        $prevTotalPaid = session('total_paid_' . $transaction->name, null);
        $currentTotalPaid = $transaction->total_paid;
        $changed = ($prevTotalPaid !== null && $prevTotalPaid !== $currentTotalPaid);
        @endphp
        @if ($changed)
        @php
        $color = ($currentTotalPaid > $prevTotalPaid) ? 'green' : 'red';
        $arrowColor = ($currentTotalPaid > $prevTotalPaid) ? 'green' : 'red';
        $arrow = ($currentTotalPaid > $prevTotalPaid) ? '▲' : '▼';
        @endphp
      <span style="color: {{ $arrowColor }}">KD<b> <a style="text-decoration:none; color: {{ $arrowColor }}"  href="/sales/{{ $transaction->omega_id }}"> {{ $transaction->total_paid }}</a></b>{{ $arrow }}</span>
      KD&nbsp;<span>{{$prevTotalPaid}}</span>
        @else
        <b> <a class="text-decoration-none @if($redcolor == 1) text-danger @else text-dark @endif" href="/sales/{{ $transaction->omega_id }}"> {{ $transaction->total_paid }} {{ __('translationFile.kd') }}</a></b>
        @endif
        @php
        session(['total_paid_' . $transaction->name => $transaction->total_paid]);
        @endphp


      
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