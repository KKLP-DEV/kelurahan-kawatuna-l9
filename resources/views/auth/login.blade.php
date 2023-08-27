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

        .success-message {
            color: green;
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
            <h2>Login</h2><br><br>
        </div>
        <div id="error-message" class="error-message"></div>
        <div id="success-message" class="success-message" style="display: none;"></div>
        <form class="login-form" id="login-form">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" name="email" id="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
            <button>Login</button>
        </form>
        <br>
        <a href="{{ url('/register') }}">Register</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var formTambah = $('#login-form');
            var errorMessage = $('#error-message');
            var successMessage = $('#success-message');

            formTambah.on('submit', function(e) {
                e.preventDefault();
                errorMessage.empty();
                successMessage.hide();

                var formData = new FormData(this);


                $('#loading-overlay').show();
                $.ajax({
                    type: 'POST',
                    url: '{{ url('v4/396d6585-16ae-4d04-9549-c499e52b75ea/auth/login') }}',
                    data: formData,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    dataType: 'JSON',

                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#loading-overlay').hide();
                        if (data.message === 'Invalid email or password' | data.message ===
                            'Email not verified') {
                            var error = data.errors;
                            var errorMessageText = "Email or password not valid";

                            $.each(error, function(key, value) {
                                errorMessageText += value[0] + "<br>";
                            });

                            showErrorAlert(errorMessageText);
                        } else {
                            console.log(data);
                            showSuccessAlert('Success login', '/');
                        }
                    },
                    error: function(data) {
                        $('#loading-overlay').hide();
                        var error = data.responseJSON.errors;
                        var errorMessageText = "";

                        $.each(error, function(key, value) {
                            errorMessageText += value[0] + "<br>";
                        });

                        showErrorAlert(errorMessageText);
                    }
                });
            });

            function showErrorAlert(message) {
                $('#loading-overlay').hide();
                errorMessage.html(message);
            }

            function showSuccessAlert(message, redirectUrl) {
                $('#loading-overlay').hide();
                successMessage.html(message).show();
                setTimeout(function() {
                    successMessage.hide();
                }, 3000);

                window.location.href = redirectUrl;
            }
        });
    </script>
@endsection
