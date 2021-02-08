<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Registration</title>
    <link rel="stylesheet" href="{{ asset('styles/bootstrap-3.1.1/css/bootstrap.min.css') }}" />
</head>

<body>
    <div class="container">
        <div class="row" style="margin-top:45px">
            <div class="col-md-4 col-md-offset-4">
                <form action="{{ route('auth.create') }}" method="post">
                    @csrf
                    <div class="results">
                        @if (Session::get('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        @if (Session::get('fail'))
                            <div class="alert alert-danger">
                                {{ Session::get('fail') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <h4>User Register</h4>
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Name"
                            value="{{ old('name') }}">
                        <span class="text-danger">@error('name') {{ $message }} @enderror</span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" placeholder="Enter Email"
                            value="{{ old('email') }}">
                        <span class="text-danger">@error('email') {{ $message }} @enderror</span>
                    </div>

                    <div class="form-group">
                        <label for="email">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter Password">
                        <span class="text-danger">@error('password') {{ $message }} @enderror</span>
                    </div>

                    <div class="form-group">
                        <label for="email">Password confirmation</label>
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Enter Password Confirmation">
                        <span class="text-danger">@error('password_confirmation') {{ $message }} @enderror</span>
                    </div>

                    <div class="form-group">
                        <label for="email">Phone Number</label>
                        <input type="text" class="form-control" name="phone_number" placeholder="Enter Phone Number">
                        <span class="text-danger">@error('phone_number') {{ $message }} @enderror</span>
                    </div>

                    <div class="form-group">

                        <input class="form-check-input" type="checkbox"  name="terms">
                        <span class="text-danger">@error('terms') {{ $message }} @enderror</span>
                        <label class="form-check-label" for="terms">
                            Accept our term and services <a href="{{ route('term.last') }}" target="_blank"> View
                                Terms</a>
                        </label>


                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary">Register</button>
                    </div>
                    <br />
                    <a href="login">I already have account</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
