@include('home.header')
<div class="container">

    <div class="col-xl-12 mx-auto mt-5">

        @error('message')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        @if(Session::has('success'))
            <div class="alert alert-success mt-5">
                {{Session::get('success')}}
            </div>
        @endif

        @if(Session::has('error'))
            <div class="alert alert-error mt-5">
                {{Session::get('error')}}
            </div>
        @endif

        <div class="bg-secondary rounded h-100 p-4">
            <h4 class="mb-4">Company Register Form</h4>
            <form action="{{ route('register-company') }}" method="post" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control" name="name">
                        @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Website</label>
                        <input type="text" class="form-control" name="website">
                        @if ($errors->has('website'))
                        <span class="text-danger">{{ $errors->first('website') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="exampleInputEmail1" class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp">
                        @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                        @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">License number</label>
                        <input type="text" class="form-control" name="license_number">
                        @if ($errors->has('license_number'))
                        <span class="text-danger">{{ $errors->first('license_number') }}</span>
                        @endif
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Country</label>
                        <select name="country" class="form-select mb-3 country">
                            <option selected disabled> Select Country </option>
                            @foreach ($countries as $country)
                            <option value="{{ $country }}">{{ $country }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger text-danger-country"></span>
                        @if ($errors->has('country'))
                        <span class="text-danger">{{ $errors->first('country') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" id="" cols="5" rows="3" class="form-control"></textarea>
                        @if ($errors->has('address'))
                        <span class="text-danger">{{ $errors->first('address') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">State</label>
                        <select name="state" class="form-select mb-3 state">
                            <option selected disabled> Select State </option>
                        </select>
                        <span class="text-danger text-danger-state"></span>
                        @if ($errors->has('state'))
                        <span class="text-danger">{{ $errors->first('state') }}</span>
                        @endif
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">City</label>
                        <select name="city" class="form-select mb-3 city">
                            <option selected disabled> Select City </option>
                        </select>
                        @if ($errors->has('city'))
                        <span class="text-danger">{{ $errors->first('city') }}</span>
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </div>
</div>


<script>
    $(document).ready(function() {
        // $(".text-danger-box").hide();
        /*------------------------------------------
        --------------------------------------------
        Country Dropdown Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('.country').on('change', function() {
            var countryName = this.value;
            $.ajax({
                url: "{{url('/changeLocation/state')}}",
                type: "POST",
                data: {
                    country: countryName,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    $('.state').html('<option value="" selected disabled> Select State </option>');
                    $.each(result, function(key, value) {
                        $(".state").append('<option value="' + value + '">' + value + '</option>');
                    });
                },
                error: function(result) {
                    $(".text-danger-country").text(result.responseText);
                }
            });
        });


        /*------------------------------------------
            --------------------------------------------
            State Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/
        $('.state').on('change', function() {
            var stateName = this.value;
            var countryName = $('.country').val();
            $.ajax({
                url: "{{url('/changeLocation/city')}}",
                type: "POST",
                data: {
                    country: countryName,
                    state: stateName,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    $('.city').html('<option value="" selected disabled> Select City </option>');
                    $.each(result, function(key, value) {
                        $(".city").append('<option value="' + value + '">' + value + '</option>');
                    });
                },
                error: function(result) {
                    $(".text-danger-state").text(result.responseText);
                }
            });
        });


    });
</script>

@include('home.footer')