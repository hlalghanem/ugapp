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
  
@endphp
@endauth
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

</style>

  <div id="accordion">
    <div class="card my-1">
      <div class="card-header">
        <div class="row ">
          <div class="col-6">
            @if($language === 'en')  <h2>{{ $branchinfo->name }}</h2> @else   <h2>{{ $branchinfo->name_ar }}</h2> @endif
          </div>
          <div class="col-6 @if($language === 'en') text-end @else text-start @endif">
            @if (date('Y-m-d', strtotime($brTodayTotal->eod_date)) != date('Y-m-d'))
            <h3><span class="badge text-bg-warning">  @if($language === 'en')  {{ \Carbon\Carbon::parse($brTodayTotal->eod_date)->format('D j M') }}  @else    {{ \Carbon\Carbon::parse($brTodayTotal->eod_date)->isoFormat('dddd D MMMM') }} @endif</span></h3>
            @else
            <h3><span class="badge text-bg-info">  @if($language === 'en')  {{ \Carbon\Carbon::parse($brTodayTotal->eod_date)->format('D j M') }}  @else    {{ \Carbon\Carbon::parse($brTodayTotal->eod_date)->isoFormat('dddd D MMMM') }} @endif</span></h3>
            @endif
          </div>
        </div>     
        <div class="row ">
          
          <div class="col-1">
           
          </div>
          <div class="col-10 text-center">

            <span class="badge text-warning"> <b>{{ __('translationFile.sales_History') }} </b></span>
          </div>
          <div class="col-1 text-end">
         
          </div>
        </div>     
    </div>
  </div>  

    @if(count($totals)>0)
    <div class="card mb-1">
      <div class="card-header">
    
        <a class="btn" data-bs-toggle="collapse" href="#collapseOne">
        <b>   {{ __('translationFile.rep_Sales_By_Payment') }} {{ $brTodayTotal->total_amount }} ▼</b>
        
        </a>
      
      </div>
      <div id="collapseOne" class="collapse show" data-bs-parent="#accordion">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
        
                  <th scope="col"> {{ __('translationFile.payment') }}</th>
                  <th scope="col"> {{ __('translationFile.total') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($totals as $total)
                <tr>
                  <td>{{ $total->payment_type }}</td>
        <td>{{ $total->total_amount }} </td>
        
        
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <td><b> {{ __('translationFile.grand_total') }}</b></td>
                  <td>
                      <b>
                        
                  KD {{ $brTodayTotal->total_amount }}</b>
                   
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
           
        @if ($voids<0)
        <div class="card border-danger mb-1">
          <div class="card-body">
            <div class="row text-danger">
              <div class="col-8">
                {{ __('translationFile.voids_total') }}{{number_format($voids, 3) }}
              </div>
              <div class="col-4 @if($language === 'en') text-end @else text-start @endif">
            <i class="bi bi-exclamation"  style="font-size:26px;"></i>
              </div>
            </div>     
        </div>
    </div>  
    @endif
     

    {{-- Discount and Refund --}}
    @if ($discount->discount >0)
    <div class="card border-warning mb-1">
      <div class="card-body">
        <div class="row text-warning">
          <div class="col-8">
            {{ __('translationFile.discount_total') }} {{ $discount->discount }}   
          </div>
          <div class="col-4 @if($language === 'en') text-end @else text-start @endif">
        <i class="bi bi-cash-coin"  style="font-size:26px;"></i>
          </div>
        </div>     
    </div>
  </div>  
  @endif
  @if ($refund->refund <0)
  <div class="card border-danger ">
    <div class="card-body">
      <div class="row text-danger ">
        <div class="col-8">
          {{ __('translationFile.refund_total') }} {{ $refund->refund }}   
        </div>
        <div class="col-4 @if($language === 'en') text-end @else text-start @endif">
      <i class="bi bi-exclamation-triangle"  style="font-size:26px;"></i>
        </div>
      </div>     
  </div>
</div> 
  @endif
{{-- End. Discount and Refund --}}
</div> </div> </div>
   

    <div class="card mb-1">
      <div class="card-header">
        <a class="collapsed btn" data-bs-toggle="collapse" href="#collapseTwo">
          <b> {{ __('translationFile.sales_By_Menu') }} ▼</b>
      </a>
      </div>
      <div id="collapseTwo" class="collapse" data-bs-parent="#accordion">
        <div class="card-body">
          <table class="table">
      
            <tbody>
            
              @foreach ($menu as $m)
              <tr>
                <td>{{ $m->menu }}</td>
                <td>KD {{ $m->total_menu }}</td>
               
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="card mb-1">
      <div class="card-header">
        <a class="collapsed btn" data-bs-toggle="collapse" href="#collapseThree">
          <b>  {{ __('translationFile.sales_By_Employee') }} ▼</b>
        </a>
      </div>
      <div id="collapseThree" class="collapse" data-bs-parent="#accordion">
        <div class="card-body">
          <table class="table">
      
            <tbody>
            
              @foreach ($employee as $emp)
              <tr>
                <td>{{ $emp->employee }}</td>
                <td>KD {{ $emp->total_employee }}</td>
               
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
    @else
    <div class="card mb-1">
      <div class="card-header">
    <a class="btn" data-bs-toggle="collapse" href="#collapseOne">
      <b>  {{ __('translationFile.rep_Sales_By_Payment') }}  ▼</b>
      </a>
    </div>
    <div id="collapseOne" class="collapse show" data-bs-parent="#accordion">
      <div class="card-body">
        <p class="m-2">{{ __('translationFile.noTransactions') }}</p>
     
      </div>
    </div>
  </div>
   
    @endif


 
   <br>

  
    <div class="text-center m-1"> <button class="btn btn-outline-secondary" onclick="goBack()"> {{ __('translationFile.goBack') }}</button>
      <script>
        function goBack() {
          window.history.back();
        }
      </script>
        </div>
   
  <br>

 
</div>



@endsection