@extends('layouts.main-layout')
@section('content')

    <br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card ">
                    <div class="card-header ">{{ __('404 Error') }}</div>

                    <div class="card-body">
                        {{ $exception->getMessage() ?: __('Sorry, the page you are looking for could not be found.') }}
                    </div>
                </div>
            </div>
        </div>

@endsection
