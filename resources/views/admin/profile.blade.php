<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Profile</title>
    <link rel="stylesheet" href="{{ asset('styles/bootstrap-3.1.1/css/bootstrap.min.css') }}" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {

            jQuery.fn.fadeOutAndRemove = function(speed) {
                $(this).fadeOut(speed, function() {
                    $(this).remove();
                })
            }
            $("body").on("click", "#button", function(e) {

                if (!confirm("Do you really want to delete this?")) {
                    return false;
                }

                e.preventDefault();

                var id = $(this).attr('data-id');
                var token = $("meta[name='csrf-token']").attr("content");
                var url = e.target;

                $.ajax({
                    url: 'userDelete/' + id, //or you can use url: "company/"+id,
                    type: 'DELETE',
                    data: {
                        _token: token,
                        id: id
                    },
                    success: function(response) {
                        //console.log($(this).attr("id"));

                        // $("#notification").fadeOut("normal", function() {
                        $("#" + id).fadeOutAndRemove('normal');
                        // });
                        $("#success").html(response.message)


                    }
                });
                return false;
            });


        });

    </script>
</head>

<body>
    <div class="container">

        <div class="row" style="margin-top:45px">

            <div class="col-md-6 col-md-offset-3">
                <table class="table table-hover">
                    <thead>
                        <th>
                            <h4>Logged User</h4>
                        </th>
                        <th>
                            <h4>Email</h4>
                        </th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $LoggedUserInfo->name }}</td>
                            <td>{{ $LoggedUserInfo->email }}</td>
                            <td><a href="{{ route('terms.index') }}">Go to Terms Conditions</a></td>

                            <td><a href="logout">Logout</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 col-md-offset-3">
                <h4>System Users Table</h4>

                <div class="form-group">
                    <label>Type words to search</label>
                    <input type="text" name="words" id="words" placeholder="Enter words to search" class="form-control">
                </div>

                <div class="col-lg-3"></div>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
                    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
                    crossorigin="anonymous"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
                    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
                    crossorigin="anonymous"></script>

                <script type="text/javascript">
                    $(document).ready(function() {

                        $('#words').on('keyup', function() {
                            var query = $(this).val();
                            $.ajax({

                                url: "{{ route('users.search') }}",
                                type: "GET",
                                data: {
                                    'words': query
                                },
                                success: function(data) {
                                    $('#users_list').html(data);
                                }
                            })

                        });

                    });

                </script>

                <table id="users_list" class="table table-bordered">
                    <thead>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th width="350px">Action</th>
                    </thead>

                    @foreach ($users as $user)
                        <tr id="{{ $user->id }}">
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_number }}</td>
                            <td>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                    <a class="btn btn-primary" href="{{ route('users.edit', $user->id) }}">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" id="button" data-id="{{ $user->id }}"
                                        class="btn btn-danger">Delete</button>
                                    @if ($user->is_verified)
                                        <a class="btn btn-warning"
                                            href="{{ route('users.unverify', $user->id) }}">Un-verify</a>
                                    @endif

                                </form>
                            </td>
                        </tr>
                    @endforeach

                </table>
                {{ $users->links() }}
            </div>
        </div>
    </div>



    </div>
</body>

</html>
