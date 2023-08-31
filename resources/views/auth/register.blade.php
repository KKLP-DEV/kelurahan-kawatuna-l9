@extends('Layouts.loginBase')
@section('content')
    <style>
        /* Add the CSS for the loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: none;
        }

        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .loading img {
            max-width: 100px;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div id="loading" class="loading">
            <img src="{{ asset('img/loader.gif') }}" alt="Loading..." />
        </div>
    </div>
    <div class="login-card">
        <div class="header">
          <h2>Register</h2><br>
        </div>
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
                      placeholder="Password Konfirmasi">
              </div>
              <button>Register</button>
              <a href="{{ url('/login') }}">Login</a>
          </form>
      </div>
    <!-- /.login-box -->
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

                // Show the loading overlay
                $('#loading-overlay').show();

                $.ajax({
                    url: '{{ url('v4/396d6585-16ae-4d04-9549-c499e52b75ea/auth/register') }}',
                    type: 'POST',
                    data: {
                        name: name,
                        email: email,
                        password: password,
                        password_confirmation: password_confirmation,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#loading-overlay').hide();
                        console.log(data);
                        if (data.code === 400) {
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

                        } else {
                            $('#loading-overlay').hide();
                            console.log(data);
                            Swal.fire({
                                title: 'Success',
                                text: 'Registrasi sukses silahkan check email anda',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location.href = '/login';
                            });
                        }
                    },
                    error: function(data) {
                        $('#loading-overlay').hide();
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
