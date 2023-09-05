@extends('Layouts.Base')
@section('content')
    <style>
        .account-settings .user-profile {
            margin: 0 0 1rem 0;
            padding-bottom: 1rem;
            text-align: center;
        }

        .account-settings .user-profile .user-avatar {
            margin: 0 0 1rem 0;
        }

        .account-settings .user-profile .user-avatar img {
            width: 90px;
            height: 90px;
            -webkit-border-radius: 100px;
            -moz-border-radius: 100px;
            border-radius: 100px;
        }

        .account-settings .user-profile h5.user-name {
            margin: 0 0 0.5rem 0;
        }

        .account-settings .user-profile h6.user-email {
            margin: 0;
            font-size: 0.8rem;
            font-weight: 400;
            color: #9fa8b9;
        }

        .account-settings .about {
            margin: 2rem 0 0 0;
            text-align: center;
        }

        .account-settings .about h5 {
            margin: 0 0 15px 0;
            color: #007ae1;
        }

        .account-settings .about p {
            font-size: 0.825rem;
        }

        .form-control {
            border: 1px solid #cfd1d8;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
            font-size: .825rem;
            background: #ffffff;
            color: #2e323c;
        }

        .card {
            background: #ffffff;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            border: 0;
            margin-bottom: 1rem;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 mb-4">
            <!-- Simple Tables -->
            <div class="card">
                <div class="row gutters">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="account-settings">
                                    <div class="user-profile">
                                        <div class="user-avatar">
                                            <img src="{{ asset('admin/img/boy.png') }}"
                                                alt="Maxwell Admin">
                                        </div>
                                        <h5 class="user-name" id="name"></h5>
                                        <h6 class="user-email" id="email"></h6>
                                    </div>
                                    <div class="about">
                                        <h5></h5>
                                        <p>Silahkan ganti password anda</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row gutters">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <h6 class="mb-2 text-primary">Setting</h6>
                                    </div>
                                    <form id="formEdit" method="POST" class="w-100" enctype="multipart/form-data">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fullName">Password Lama</label>
                                                <input type="password" class="form-control" name="password_old"
                                                    id="password_old" placeholder="Input here">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fullName">Password Baru</label>
                                                <input type="password" class="form-control" name="password"
                                                    placeholder="Input here">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fullName">Konfirmasi password</label>
                                                <input type="password" class="form-control" name="password_confirmation"
                                                    placeholder="Input here">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="row gutters">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="text-right">
                                            <button type="button" id="submit" name="submit"
                                                class="btn btn-secondary">Cancel</button>
                                            <button type="submit" form="formEdit" class="btn btn-outline-primary">Update
                                                Data</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: '{{ url('get/user') }}',
                dataType: 'JSON',
                success: function(data) {
                    console.log('user disini +>', data);
                    $('#name').text(data.data.name);
                    $('#email').text(data.data.email);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }

            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            var formEdit = $('#formEdit');

            formEdit.on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                // Tampilkan loader
                $('#loading-overlay').show();
                $.ajax({
                    type: 'POST',
                    url: '{{ url('change-password') }}',
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        $('#loading-overlay').hide();
                        if (data.message === 'check your validation') {
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
                        } else if (data.code === 404) {
                            var error = data.errors;
                            var errorMessage = "Password Lama salah";

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

                $(document).on('keydown', function(e) {
                    if (e.which === 13 && $('.swal2-modal').is(':visible')) {
                        $('.swal2-confirm').click();
                    }
                });
            });
        });
    </script>
@endsection
