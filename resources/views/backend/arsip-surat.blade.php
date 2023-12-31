@extends('Layouts.Base')
@section('content')
    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div id="loading" class="loading">
            <img src="{{ asset('img/loader.gif') }}" alt="Loading..." />
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 id="page_title" class="m-0 font-weight-bold text-primary"></h6>
            </div>
            <div class="p-3">
                <div class="row" id="data-container">
                    <div class="table-responsive p-3">
                        <table id="dataTable" class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Yang mengupload</th>
                                    <th>Nomor Surat</th>
                                    <th>Tanggal Arsip</th>
                                    <th>Tahun Arsip</th>
                                    <th>Jenis surat</th>
                                    <th>File surat</th>
                                    <th>Perihal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data from database will be shown here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal edit --}}
    <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditModalLabel">Edit Scholarship</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEdit" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="uuid" id="uuid">
                        <div class="form-group">
                            <label for="nomor_surat">Nomor Surat</label>
                            <input type="text" class="form-control" name="nomor_surat" id="enomor_surat"
                                placeholder="Input Here..">
                        </div>
                        <div class="form-group">
                            <label for="tanggal_surat">Tanggal Arsip</label>
                            <input type="date" class="form-control" name="tanggal_surat" id="etanggal_surat"
                                placeholder="Input Here">
                        </div>
                        <div class="form-group">
                            <label for="id_tahun">Tahun Arsip</label>
                            <select name="id_tahun" id="eid_tahun" class="form-control">
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="id_jenis_surat">Jenis Surat</label>
                            <select name="id_jenis_surat" id="eid_jenis_surat" class="form-control">
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="file_surat">File</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="efile_surat" name="file_surat">
                                <label class="custom-file-label" for="efile_surat" id="efile_surat-label">File </label>
                                <p>Format: Jpg,jpeg,png,doc,docx,xls,xlsx,pdf</p>
                            </div>
                            <img src="" alt="" id="preview" class="mx-auto d-block pb-2"
                                style="max-width: 200px; padding-top: 23px">
                        </div>
                        <div class="form-group">
                            <label for="perihal"> Perihal</label>
                            <textarea type="text" class="form-control" name="perihal" id="eperihal" placeholder="Input Here" rows="3"> </textarea>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                    <button type="submit" form="formEdit" class="btn btn-outline-primary">Update Data</button>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>


    <script>
        // Mendapatkan ID dari URL terakhir
        var segments = window.location.pathname.split('/');
        var id = segments[segments.length - 1];
        $.ajax({
            url: '{{ url('v2/396d6585-16ae-4d04-9549-c499e52b75ea/jenis/surat/get/id') }}/' + id,
            type: 'GET',
            success: function(response) {
                console.log(response)
                $('#page_title').text(response.data.jenis_surat);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });


        $(document).ready(function() {
            var url = new URL(window.location.href);
            var id_tahun = url.pathname.split('/')[6];
            var id_jenis_surat = url.pathname.split('/')[7];
            // Tampilkan loader
            $('#loading-overlay').show();
            $.ajax({
                url: "{{ url('v3/396d6585-16ae-4d04-9549-c499e52b75ea/surat-arsip/user') }}/" + id_tahun +
                    "/" + id_jenis_surat,
                method: "GET",
                dataType: "json",
                success: function(response) {
                    $('#loading-overlay').hide();
                    console.log('surat masuk => ', response);
                    var tableBody = "";
                    $.each(response.data, function(index, item) {
                        tableBody += "<tr>";
                        tableBody += "<td>" + (index + 1) + "</td>";
                        tableBody += "<td>" + item.users.name + "</td>";
                        tableBody += "<td>" + item.nomor_surat + "</td>";
                        tableBody += "<td>" + item.tanggal_surat + "</td>";
                        tableBody += "<td>" + item.tahun.tahun + "</td>";
                        tableBody += "<td>" + item.jenis_surat.jenis_surat + "</td>";
                        tableBody += "<td>";
                        tableBody +=
                            `<a href="/uploads/smasuk/${item.file_surat}" class="btn btn-primary" target="_blank"><i class="fa fa-eye"></i></a>`;
                        tableBody += "</td>";
                        tableBody += "<td>" + item.perihal + "</td>";
                        tableBody += "<td>" +
                            "<button type='button' class='btn btn-primary edit-modal' data-toggle='modal' data-target='#EditModal' " +
                            "data-uuid='" + item.uuid + "' " +
                            "<i class='fa fa-edit'>Edit</i></button>" +
                            "<button type='button' class='btn btn-danger delete-confirm' data-uuid='" +
                            item.uuid + "'><i class='fa fa-trash'></i></button>" +
                            "</td>";

                        tableBody += "</tr>";
                    });
                    $('#dataTable').DataTable().destroy();
                    $("#dataTable tbody").empty();
                    $("#dataTable tbody").append(tableBody);
                    $('#dataTable').DataTable({
                        "paging": true,
                        "ordering": true,
                        "searching": true
                    });
                },
                error: function() {
                    $('#loading-overlay').hide();
                    console.log("Failed to get data from server");
                }
            });
        });



        //get data tahun for option
        $.ajax({
            url: "{{ url('v1/396d6585-16ae-4d04-9549-c499e52b75ea/tahun') }}",
            method: "GET",
            dataType: "json",
            success: function(response) {
                var options = '';
                $.each(response.data, function(index, item) {
                    options += '<option value="' + item.id +
                        '">' + item.tahun + '</option>';
                });
                $('#eid_tahun').append(options);

            },
            error: function() {
                console.log("Failed to get data from server");
            }
        });

        //get data jenis surat for option
        $.ajax({
            url: "{{ url('v2/396d6585-16ae-4d04-9549-c499e52b75ea/jenis/surat') }}",
            method: "GET",
            dataType: "json",
            success: function(response) {
                console.log(response);
                var options = '';
                $.each(response.data, function(index, item) {
                    options += '<option value="' + item.id +
                        '">' + item.jenis_surat + '</option>';
                });
                $('#eid_jenis_surat').append(options);

            },
            error: function() {
                console.log("Failed to get data from server");
            }
        });

        //edit
        $(document).on('click', '.edit-modal', function() {
            var uuid = $(this).data('uuid');
            // Menampilkan nama file gambar saat dipilih
            $(document).on('change', '#efile_surat', function() {
                var fileName = $(this).val().split('\\').pop();
                $('#efile_surat-label').text(fileName);
            });

            $.ajax({
                url: "{{ url('v3/396d6585-16ae-4d04-9549-c499e52b75ea/surat-arsip/get') }}/" + uuid,
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    console.log('response get data by uuid =>>', data);
                    $('#uuid').val(data.data.uuid);
                    $('#enomor_surat').val(stripHtmlTags(data.data.nomor_surat));
                    $('#etanggal_surat').val((data.data.tanggal_surat));
                    $('#efile_surat').html(data.data.file_surat);
                    $('#preview').attr('src', "{{ asset('uploads/smasuk') }}/" + data.data
                        .file_surat);
                    $('#eid_jenis_surat').val(stripHtmlTags(data.data.id_jenis_surat));
                    $('#eid_tahun').val(data.data.id_tahun);
                    $('#eperihal').val(stripHtmlTags(data.data.perihal));
                    // Tampilkan nama file gambar pada label
                    var fileName = data.data.file_surat.split('/').pop();
                    $('#efile_surat-label').text(fileName);
                    $('#EditModal').modal('show');
                },
                error: function() {
                    alert("error");
                }
            });
        });
        // Fungsi untuk menghapus tag HTML dari teks
        function stripHtmlTags(text) {
            var div = document.createElement("div");
            div.innerHTML = text;
            return div.textContent || div.innerText || "";
        }



        //update
        $(document).ready(function() {
            var formEdit = $('#formEdit');

            formEdit.on('submit', function(e) {
                e.preventDefault();

                var uuid = $('#uuid').val();
                var formData = new FormData(this);
                // Tampilkan loader

                var file = $('#efile_surat')[0].files[0];
                if (!file) {
                    formData.delete('file_surat');
                }
                $('#loading-overlay').show();
                var selectedDate = moment($('#etanggal_surat').val(), 'YYYY-MM-DD').format('DD MMMM YYYY');
                formData.set('tanggal_surat', selectedDate);

                $.ajax({
                    type: "POST",
                    url: "{{ url('v3/396d6585-16ae-4d04-9549-c499e52b75ea/surat-arsip/update') }}/" +
                        uuid,
                    data: formData,
                    dataType: 'json',
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
                        } else {
                            console.log(data);
                            $('#loading-overlay').hide();
                            Swal.fire({
                                title: 'Success',
                                text: 'Data Success Update',
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
                        var errors = data.responseJSON.errors;
                        var errorMessage = "";

                        $.each(errors, function(key, value) {
                            errorMessage += value + "<br>";
                        });

                        Swal.fire({
                            title: "Error",
                            html: errorMessage,
                            icon: "error",
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

        //delete
        $(document).on('click', '.delete-confirm', function(e) {
            e.preventDefault();
            var uuid = $(this).data('uuid');
            Swal.fire({
                title: 'Anda yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Delete',
                cancelButtonText: 'Cancel',
                resolveButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('v3/396d6585-16ae-4d04-9549-c499e52b75ea/surat-arsip/delete') }}/" +
                            uuid,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "uuid": uuid
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.code === 200) {
                                Swal.fire({
                                    title: 'Data berhasil dihapus',
                                    icon: 'success',
                                    timer: 5000,
                                    showConfirmButton: true
                                }).then((result) => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal menghapus data',
                                    text: response.message,
                                    icon: 'error',
                                    timer: 5000,
                                    showConfirmButton: true
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Terjadi kesalahan',
                                text: 'Gagal menghapus data',
                                icon: 'error',
                                timer: 5000,
                                showConfirmButton: true
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
