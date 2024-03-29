<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Q8crm</title>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    {{-- <meta name="apple-mobile-web-app-status-bar-style" content="black"> --}}
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
   
   
  @php
    $language = auth()->check() ? auth()->user()->lang : 'en'; // Get the language from the authenticated user or use a default value
    $isRTL = $language === 'ar'; // Check if the language is Arabic
@endphp
<style>
  .footer {
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    background-color: #f8f8f8;
    padding: 20px;
}

</style>
@if($isRTL)
    <style>
        body {
            direction: rtl;
        }
    </style>
@endif 
@auth
@php
    $user = auth()->user();
    $language = $user->lang;
    app()->setLocale($language);
@endphp
@endauth
  </head>
  <body>
 


    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
          <a class="navbar-brand text-warning" href="/"><i><b>Q8</b>CRM</i></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/"> {{ __('translationFile.homePage') }}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/myallbranches"> {{ __('translationFile.mybranches') }}</a>
              </li>
              
               @auth
                 @if (auth()->user()->group_id == 2)
                 <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Admin
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/branch/all">All Branches</a></li>
                    <li><a class="dropdown-item" href="/branch/users">Branch Users</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/users">Users</a></li>
                  </ul>
                </li>
                
                 @endif
                 @if (auth()->user()->group_id == 2 or auth()->user()->group_id == 3)
                 <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Staff
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/branch/all">All Branches</a></li>
                  
                  </ul>
                </li>
                 @endif
               @endauth

               <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ __('translationFile.reports') }}
                    
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/reports/salesbydate">  {{ __('translationFile.rep_Sales_By_Payment') }}</a></li>
                    <li><a class="dropdown-item" href="/reports/salessummary">  {{ __('translationFile.rep_Sales_Summary') }}</a></li>
                    <li><a class="dropdown-item" href="/reports/summarybyday">  {{ __('translationFile.summarybyday') }}</a></li>
                    <li><a class="dropdown-item" href="/reports/completereport">  {{ __('translationFile.complete_report') }}</a></li>
                  </ul>
                </li>
              
              
              @if (1>2)
              <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
              </li>
              <li class="nav-item">
                <a class="nav-link disabled">Disabled</a>
              </li>


              @endif
            </ul>
            @if (Route::has('login'))
            <div class="d-flex my-2 my-sm-0" role="search">
                    @auth

                 
                     <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu ">
                                <li> <a href="{{ route('users.setLanguageAr') }}" class="dropdown-item">العربية</a></li>
                                <li> <a href="{{ route('users.setLanguageEn') }}" class="dropdown-item">English</a></li>
                              
                              <li> <a href="{{ route('logout') }}" class="dropdown-item">{{ __('translationFile.logout') }}</a></li>
                            </ul>
                          </li>
                    </ul>
                    
                  
                       
                    @else
                    <a href="{{ route('login') }}" class="nav-link active">Log in</a>
                    @endauth
                </div>
            @endif


                
              
           
          </div>
        </div>
      </nav>
<div class="container">

@yield('content')
{{-- <footer class="footer">
  <p class="text-center"></p>
</footer> --}}

    <br><br>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
   
  </body>
</html>