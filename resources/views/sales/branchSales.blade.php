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

{{-- @php
    use Carbon\Carbon;

    // Set the locale to Arabic
    Carbon::setLocale('ar');

    // Create a Carbon instance with the desired date
    $date =$branchinfo->last_eod;

    // Format the date in Arabic
    $arabicFormattedDate = $date->isoFormat('ddd، D MMMM');
@endphp

<p>{{ $arabicFormattedDate }}</p>
 --}}

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

  <div id="accordion">


    <div class="card my-1">
      <div class="card-header">
        <div class="row ">
          <div class="col-6">
            @if($language === 'en')  <h2>{{ $branchinfo->name }}</h2> @else   <h2>{{ $branchinfo->name_ar }}</h2> @endif
          
          </div>
          <div class="col-6 @if($language === 'en') text-end @else text-start @endif">
          
            @if (date('Y-m-d', strtotime($branchinfo->last_eod)) != date('Y-m-d'))
            <h3><span class="badge text-bg-warning">  @if($language === 'en')  {{ \Carbon\Carbon::parse($branchinfo->last_eod)->format('D j M') }}  @else    {{ \Carbon\Carbon::parse($branchinfo->last_eod)->isoFormat('dddd D MMMM') }} @endif</span></h3>
            @else
            <h3><span class="badge text-bg-info">  @if($language === 'en')  {{ \Carbon\Carbon::parse($branchinfo->last_eod)->format('D j M') }}  @else    {{ \Carbon\Carbon::parse($branchinfo->last_eod)->isoFormat('dddd D MMMM') }} @endif</span></h3>
            @endif
          </div>
        </div>     
        <div class="row ">
          
          <div class="col-4">
            @if (strtotime($branchinfo->last_sync) < strtotime('-30 minutes')) 
               
            <span class="badge bg-danger">{{ __('translationFile.offline') }}</span>
               
                @endif 
         
          </div>
          <div class="col-4 text-center">
            <button class="refresh-btn">
              <span class="spinner"></span>
              <i class="bi bi-arrow-clockwise"></i>
              {{ __('translationFile.refresh') }}
            </button>
          </div>
          <div class="col-4 text-end">
         
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
                     
                     {{ $brTodayTotal->total_amount }}&nbsp;
        
                  <span style="color: {{ $arrowColor }}">{{ $arrow }}KD&nbsp;{{number_format($changediff, 3)}}</span>
                    @else
                   
                      <b>
                  KD {{ $brTodayTotal->total_amount }}</b>
                    @endif
                    @php
                    session(['total_paid_' . $branchinfo->name =>  $brTodayTotal->total_amount]);
                    @endphp
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
            @if ($open_orders->open_orders >0)
            <div class="card border-info text-info mb-1 ">
              <div class="card-body">
                <div class="row ">
                  <div class="col-8">
                    {{ __('translationFile.open_orders_amount') }}{{ $open_orders->open_orders }}
                  </div>
                  <div class="col-4 @if($language === 'en') text-end @else text-start @endif">
                <i class="bi bi-receipt"  style="font-size:26px;"></i>
                  </div>
                </div>     
            </div>
        </div>  
        @endif

        @if ($branchinfo->voids <0)
        <div class="card border-danger mb-1">
          <div class="card-body">
            <div class="row text-danger">
              <div class="col-8">
                {{ __('translationFile.voids_total') }}{{ $branchinfo->voids }}
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
    <div class="card bg-warning mb-1">
      <div class="card-body">
        <div class="row ">
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
  <div class="card bg-danger ">
    <div class="card-body">
      <div class="row ">
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
      @if ((count($employee)>0))
        
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
        
      @endif
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
        @if ($open_orders->open_orders >0)
        <p class="text-info"> {{ __('translationFile.open_orders_amount') }} {{ $open_orders->open_orders }}</p>
        @endif
      </div>
    </div>
  </div>
   
    @endif
    {{-- top items --}}
    @if (count($topitems)>0)
    <div class="card mb-1">
      <div class="card-header">
        <a class="collapsed btn" data-bs-toggle="collapse" href="#collapsetopitems">
          <b> {{ __('translationFile.topitems') }} ▼</b>
      </a>
      </div>
      <div id="collapsetopitems" class="collapse" data-bs-parent="#accordion">
        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
              <th>{{ __('translationFile.itemdesc') }}</th>
              <th>KD</th>
              <th>#</th>
            </tr>
            </thead>
      
            <tbody>
            
              @foreach ($topitems as $item)
              <tr>
                <td>{{ $item->item_desc }}</td>
                <td>KD {{ $item->totalprice }}</td>
                <td>{{ $item->count }}</td>
               
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
      
    @endif


    {{--end top items --}}


  @if(count($totalsbyDate)>0)
  <div class="card mb-1">
    <div class="card-header">
  <a class="btn" data-bs-toggle="collapse" href="#collapseHistory">
    <b> {{ __('translationFile.sales_History') }}  ▼</b>
    </a>
  </div>
  <div id="collapseHistory" class="collapse" data-bs-parent="#accordion">
    <div class="card-body">
      {{ __('translationFile.last_5_days') }}
      <div class="table-responsive">
        <table class="table">
          
          <tbody>
            @foreach ($totalsbyDate as $total)
            <tr>
              <td>
                <a style="text-decoration:none" href="/sales/{{ request('omega_id') }}/{{ $total->eod_date }}">@if($language === 'en')  {{ \Carbon\Carbon::parse($total->eod_date)->format('D j M') }}  @else    {{ \Carbon\Carbon::parse($total->eod_date)->isoFormat('dddd D MMMM') }} @endif</a>
              </td>
              <td>KD {{ $total->total_amount }}</td>
            </tr>
            @endforeach
          </tbody>
    
        </table>
    </div>
    @if(count($prevSales)>0)
    <div class="row">
      <div class="col">
    <select id="date" name="date" class="form-select">
      @foreach ($prevSales as $date)
      <option value="{{ $date->eod_date }}" class="form-option"> @if($language === 'en')  {{ \Carbon\Carbon::parse($date->eod_date)->format('D j M') }}  @else    {{ \Carbon\Carbon::parse($date->eod_date)->isoFormat('dddd D MMMM') }} @endif</option>
      @endforeach
    </select>
      </div>
      <div class="col">
        <button onclick="goToDate()" class="btn btn-outline-secondary"> {{ __('translationFile.show') }}</button>
      </div>
    </div>
     @endif
  

  </div>
</div>
    @endif
  </div>
    {{-- Other info --}}
    <div class="card mb-1">
      <div class="card-header">
        <a class="collapsed btn" data-bs-toggle="collapse" href="#collapseotherinfo">
          <b> {{ __('translationFile.otherinfo') }} ▼</b>
      </a>
      </div>
      <div id="collapseotherinfo" class="collapse" data-bs-parent="#accordion">
        <div class="card-body">
          <p>Last Sync. : {{ \Carbon\Carbon::parse($branchinfo->last_sync)->format('j-M H:i ')}}</p>
          <p> Last Transaction : {{ \Carbon\Carbon::parse($branchinfo->last_transaction)->format('j-M H:i')}}</p>
        </div>
      </div>
    </div>
      
    {{-- end Other info --}}

  
 <br>
 <br>
 
  

   <script>
    function goToDate() {
      var dateSelect = document.getElementById("date");
      var selectedDate = dateSelect.options[dateSelect.selectedIndex].value;
      window.location.href = "/sales/{{ request('omega_id') }}/" + selectedDate;
    }
  </script>
   <br>

  
    <div class="text-center m-1"> <button class="btn btn-outline-secondary" onclick="goBack()"> {{ __('translationFile.goBack') }}</button>
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
      <br><br>
      <br><br>
      <br>
      <br>
      <br> <br>
      <br>
      <br>
    <form method="POST" action="{{ route('deletetodaytranactions', ['omega_id' => request('omega_id')]) }}" >
      @csrf
      @method('delete')
      <button type="submit" class="btn btn-outline-danger"> Delete Today Transactions & Reupload Fresh Data</button>
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