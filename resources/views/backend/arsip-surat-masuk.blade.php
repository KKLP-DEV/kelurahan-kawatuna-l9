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
                                    <th>Nomor Surat</th>
                                    <th>Tanggal</th>
                                    <th>Tahun Arsip</th>
                                    <th>Jenis surat</th>
                                    <th>File surat</th>
                                    <th>Asal surat</th>
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
                    <h5 class="modal-title" id="EditModalLabel">Edit Tahun</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEdit" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="uuid" id="uuid">
                        <div class="form-group">
                            <label for="nama">Tahun</label>
                            <input type="number" id="etahun" name="tahun" class="form-control" min="1900"
                                max="2099" step="1" value="2016" />
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
            var id_tahun = url.pathname.split('/')[3];
            var id_jenis_surat = url.pathname.split('/')[4];
            // Tampilkan loader
            $('#loading-overlay').show();
            $.ajax({
                url: "{{ url('v3/396d6585-16ae-4d04-9549-c499e52b75ea/surat-masuk/get') }}/" + id_tahun +
                    "/" + id_jenis_surat,
                method: "GET",
                dataType: "json",
                success: function(response) {
                    $('#loading-overlay').hide();
                    console.log(response);
                    var tableBody = "";
                    $.each(response.data, function(index, item) {
                        tableBody += "<tr>";
                        tableBody += "<td>" + (index + 1) + "</td>";
                        tableBody += "<td>" + item.nomor_surat + "</td>";
                        tableBody += "<td>" + item.tanggal_surat + "</td>";
                        tableBody += "<td>" + item.tahun.tahun + "</td>";
                        tableBody += "<td>" + item.jenis_surat.jenis_surat + "</td>";
                        tableBody += "<td>";
                        tableBody +=
                            `<a href="/uploads/smasuk/${item.file_surat}" class="btn btn-primary" target="_blank"><i class="fa fa-eye"></i></a>`;
                        tableBody += "</td>";
                        tableBody += "<td>" + item.asal_surat + "</td>";
                        tableBody += "<td>" +
                            `<a href="{{ url('cms/backend/edit/news') }}/${item.uuid}" class="btn btn-primary" role="button"><i class="fa fa-edit"></i></a>` +
                            `<button type="button" class="btn btn-danger delete-confirm" data-uuid="${item.uuid}"><i class="fa fa-trash"></i></button>` +
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
    </script>
@endsection
