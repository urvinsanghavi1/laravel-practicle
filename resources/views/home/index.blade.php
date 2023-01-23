@include('home.header')
@extends('home.side')
@section('content')
<div class="container">
    @if(Session::has('success'))
    <div class="alert alert-success mt-5">
        {{Session::get('success')}}
    </div>
    @endif

    <div class="mt-5 text-center">
        @if(auth()->user() !== null)
        <h3>YOU ARE LOGGED IN INTO MAIN PANAL</h3>
        @else
        <h3>YOU ARE LOGGED INTO COMPANY PANAL</h3>
        @endif
    </div>
</div>
@endsection

@include('home.footer')