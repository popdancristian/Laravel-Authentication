<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('styles/bootstrap-3.1.1/css/bootstrap.min.css') }}" />
</head>

<body>
    <div class="container">
        <div class="row" style="margin-top:45px">
            <div class="col-md-4 col-md-offset-4">
                <form action="{{ route('auth.check') }}" method="post">
                    @csrf
                    <div class="results">
                        @if (Session::get('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        @if (Session::get('fail'))
                            <div class="alert alert-success">
                                {!! Session::get('fail') !!}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <h4>User Login</h4>
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
                        <button type="submit" class="btn btn-block btn-primary">Login</button>
                    </div>
                    <br />
                    <a href="register">Create new Account</a>

                </form>
            </div>
        </div>
    </div>
</body>

</html>
