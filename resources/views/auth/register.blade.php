@extends('Layouts.loginBase')
@section('content')
    <div class="login-card">
        <img src="{{ asset('img/logo1.png') }}" style="max-width: 150px" alt=""><br><br><br>
        <div id="error-message" class="error-message"></div>
        <form class="login-form" id="registration-form">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" name="name" id="name" placeholder="Nama">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="email" id="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"
                    placeholder="Password">
            </div>
            <button>Register</button>
            <a href="{{ url('/login') }}">Login</a>
        </form>
    </div>

    <style>
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(function() {
            $('#registration-form').submit(function(event) {
                event.preventDefault();
                var name = $('#name').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var password_confirmation = $('#password_confirmation').val();

                $.ajax({
                    url: '{{ url('v6/396d6585-16ae-4d04-9549-c499e52b75ea/admin/create') }}',
                    type: 'POST',
                    data: {
                        name: name,
                        email: email,
                        password: password,
                        password_confirmation: password_confirmation,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.code === 422) {
                            var error = data.errors;
                            var errorMessage = "";

                            $.each(error, function(key, value) {
                                errorMessage += value[0] + "<br>";
                            });

                            Swal.fire({
                                title: 'Error',
                                html: errorMessage,
                                icon: 'error',
                                timer: 5000,
                                showConfirmButton: true
                            });
                        } else if (data.code === 400) {
                            Swal.fire({
                                title: 'Error',
                                text: 'email sudah terdaftar',
                                icon: 'error',
                                timer: 5000,
                                showConfirmButton: true
                            });
                        } else {
                            console.log(data);
                            Swal.fire({
                                title: 'Success',
                                text: 'Data Success Create',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonText: 'OK'
                            }).then(function() {
                                location.reload();
                            });
                        }
                    },
                    error: function(data) {
                        var error = data.responseJSON.errors;
                        var errorMessage = "";

                        $.each(error, function(key, value) {
                            errorMessage += value[0] + "<br>";
                        });

                        Swal.fire({
                            title: 'Error',
                            html: errorMessage,
                            icon: 'error',
                            timer: 5000,
                            showConfirmButton: true
                        });
                    }
                });
            });
        });
    </script>
@endsection
