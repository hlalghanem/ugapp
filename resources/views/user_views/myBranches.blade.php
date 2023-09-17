@extends('layouts.main-layout')
@section('content')
<br>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@php
    $language = auth()->user()->lang;
    
@endphp
@if ($language==='en')
<h2>My Branches</h2>
<p></p>
<table class="table">
    <thead>
        <tr>
           
            <th>Branch Name</th>
            <th>Status</th>
            <th>Show/Hide</th>
        </tr>
    </thead>

    <tbody>
        @foreach($userBranches as $branch)
        <tr>

            <td>{{ $branch->name }}</td>
           
            @if ($branch->is_active===1)
            <td><span class="badge text-bg-success">Active</span></td>
            <td>
                <form method="POST" action="{{ route('updateMyActiveBranches', ['branchid' => $branch->id, 'value' => '0']) }}">
                    @csrf
                    @method('put')
                    <button type="submit" title="Update" class="btn btn-outline-danger btn-sm "><i class="bi bi-toggle2-on"></i></button>
                </form>
            </td>
            @else
            <td><span class="badge text-bg-danger">Not Active</span></td>
            <td>
                <form method="POST" action="{{ route('updateMyActiveBranches', ['branchid' => $branch->id, 'value' => '1']) }}">
                    @csrf
                    @method('put')
                    <button type="submit" title="Update" class="btn btn-outline-success btn-sm "><i class="bi bi-toggle2-off"></i></button>
                </form>
            </td>
            @endif
          
             </tr>
        @endforeach
    </tbody>
</table>
@else
<h2>فروعي</h2>
<p></p>
<hr>
<table class="table">
    <thead>
        <tr>
           
            <th>اسم الفرع</th>
            <th>الحالة</th>
            <th>اظهار/اخفاء</th>
        </tr>
    </thead>

    <tbody>
        @foreach($userBranches as $branch)
        <tr>
            <td>{{ $branch->name_ar }}</td>
            @if ($branch->is_active===1)
            <td><span class="badge text-bg-success">فعال</span></td>
            <td>
                <form method="POST" action="{{ route('updateMyActiveBranches', ['branchid' => $branch->id, 'value' => '0']) }}">
                    @csrf
                    @method('put')
                    <button type="submit" title="اخفاء" class="btn btn-outline-danger btn-sm "><i class="bi bi-toggle2-off"></i></button>
                </form>
            </td>
            @else
            <td><span class="badge text-bg-danger">مخفي</span></td>
            <td>
                <form method="POST" action="{{ route('updateMyActiveBranches', ['branchid' => $branch->id, 'value' => '1']) }}">
                    @csrf
                    @method('put')
                    <button type="submit" title="تفعيل" class="btn btn-outline-success btn-sm "><i class="bi bi-toggle2-on"></i></button>
                </form>
            </td>
            @endif
          
             </tr>
        @endforeach
    </tbody>
</table>

    
@endif







@endsection