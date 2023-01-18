@include('home.header')
<div class="container">

    <div class="col-xl-6 mx-auto" style="margin-top: 250px;">

        @error('message')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="bg-secondary rounded h-100 p-4">
            <h4 class="mb-4">Login Form</h4>
            <form action="{{ route('login') }}" method="post" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp">
                    @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                    @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Sign in</button>
            </form>
        </div>

    </div>
</div>
@include('home.footer')