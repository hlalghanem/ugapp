@extends('layouts.main-layout')
@section('content')
@auth
@php
    $user = auth()->user();
    $language = $user->lang;
    app()->setLocale($language);
   
@endphp
@endauth

<div class="container mt-2">
  <div class="row">
  <h3> {{ __('translationFile.complete_report') }}</h3><hr>
    <div class="col-md-6">
      <form method="GET" action="{{ route('reports.completereport') }}">
        <div class="mb-1">
          <label for="branch" class="form-label">{{ __('translationFile.select_Branch') }}:</label>
          <select class="form-control" id="branch" name="branch">
           
            <option value="all" {{ ($branch ?? '') == 'all' ? 'selected' : '' }}>{{ __('translationFile.all_Branches') }}</option>
            @foreach($branches as $branchOption)
            @if($language === 'en')
            <option value="{{ $branchOption->id }}" {{ ($branch ?? '') == $branchOption->id ? 'selected' : '' }}>{{ $branchOption->name }}</option>
            @else
            <option value="{{ $branchOption->id }}" {{ ($branch ?? '') == $branchOption->id ? 'selected' : '' }}>{{ $branchOption->name_ar }}</option>
            @endif
            @endforeach
          </select>
        </div>
        <div class="mb-1">
          <label for="start_date" class="form-label">{{ __('translationFile.from_Date') }}</label>

          @php
          $firstDateOfMonth = now()->startOfMonth();
          @endphp
          <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $start_date ?? $firstDateOfMonth->format('Y-m-d')  }}">
        </div>
        <div class="mb-1">
          <label for="end_date" class="form-label">{{ __('translationFile.to_Date') }}</label>
          <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $end_date ?? date('Y-m-d') }}">
        </div>
        <button type="submit" class="btn btn-outline-secondary">{{ __('translationFile.show') }}</button>
       
      </form>
 
    </div>

  </div>
</div>


<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>

        <th scope="col">{{ __('translationFile.payment') }}</th>
        <th scope="col">{{ __('translationFile.total') }}</th>
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
          <td><b>{{ __('translationFile.grand_total') }}</b></td>
          <td><b>KD  {{ number_format($grand_total, 3) }}</b></td>
        </tr>

  </table>

  {{-- add here --}}
 
           
        @if ($voids->voids <0)
        <div class="card border-danger mb-1">
          <div class="card-body">
            <div class="row text-danger">
              <div class="col-8">
                {{ __('translationFile.voids_total') }}{{ $voids->voids }}
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
    
   

  
<!-- Go Back Button  -->
<button class="btn btn-outline-secondary mx-3" onclick="goBack()">{{ __('translationFile.goBack') }}</button>
    <script>
      function goBack() {
        window.history.back();
      }
    </script>
    <!-- End ---Go Back Button  -->

</div>
</div> </div> </div>
@endsection