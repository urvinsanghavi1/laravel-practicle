@include('home.header')
@include('home.side')
<div class="container">
@if(Session::has('success'))
    <div class="alert alert-success mt-5">
        {{Session::get('success')}}
    </div>
@endif
</div>
@include('home.footer')