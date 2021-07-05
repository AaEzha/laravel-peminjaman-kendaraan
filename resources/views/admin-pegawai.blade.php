<!DOCTYPE html>
<html lang="en">
@include('layouts-admin.header')

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>

            @include('layouts-admin.navbar')

            <!-- Main Content -->
            <div class="main-content" style="min-height: 633px;">
                <section class="section">
                    <div class="section-header">
                        <h1>Data Pegawai</h1>
                    </div>
                    <div class="section-body">
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-success mb-3 trigger--fire-modal-2" data-toggle="modal"
                                data-target="#modal" onclick="insert();">
                                <i class="fa fa-plus"></i> Add Data</button>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <table id="table" class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 5%">No</th>
                                            <th scope="col">NIP</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Jenis Kelamin</th>
                                            <th scope="col" style="width: 10%">Option</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div th:fragment="modal" class="modal fade bd-example-modal-lg" id="modal" tabindex="-1"
                        role="dialog" aria-labelledby="exampleModalLabel" data-keyboard="false"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Form</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="insertForm" class="px-4">
                                    <div class="modal-body">
                                        <div class="form-group justify-content-between row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>NIP</label>
                                                      <input type="number" id="nip" class="form-control p_input" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Nama</label>
                                                      <input type="text" id="nama" class="form-control p_input" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Telepon</label>
                                                      <input type="text" id="phone" class="form-control p_input" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Jenis Kelamin</label>
                                                    <select id="jenis_kelamin" class="form-control p_input">
                                                        <option value="null">-- Pilih --</option>
                                                        <option value="Laki-Laki">Laki-laki</option>
                                                        <option value="Perempuan">Perempuan</option>
                                                        <option value="Tidak Menjawab">Tidak Menjawab</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a class="btn btn-primary text-white" style="cursor: pointer"
                                            onclick="save()"><span class="fa fa-save fa-sm"></span> Simpan</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            @include('layouts-admin.footer')

        </div>
    </div>

    @include('layouts-admin.script')
    <script src="{{ asset('assets/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('assets/summernote/summernote-audio.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).off('focusin.modal');
        var table = $('#table').DataTable({
            processing: true,
            serverside: true,
            ajax: {
                type: "GET",
                url: '{{ asset('/api/pegawai/getAll') }}',
            },
            columns: [
                {data: null, name: null, orderable: false},
                {data: 'nip', name: 'nip'},
                {data: 'nama', name: 'nama'},
                {data: 'phone', name: 'phone'},
                {data: 'jenis_kelamin', name: 'jenis_kelamin'},
                {data: 'nip', name: 'nip',
                    render :function(data, type, row, meta) {
                    return type === 'display' ?
                        `<button onclick="edit('${data}')" class="btn btn-sm btn-warning mr-2 text-white">edit <span class="fa fa-edit"></span> </button>
                         <button onclick="remove('${data}')" class="btn btn-sm btn-danger text-white">delete <span class="fa fa-trash"></span></button>` :
                        data;
                    }
                }
            ],
            order: []
        });
        table.on('order.dt search.dt', function () {
            table.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

        function insert() {
            $('#nip').prop('disabled', false);
            $('#exampleModalLabel').text("Add");
            document.getElementById('insertForm').reset();
        }

        function edit(id) {
            $('#nip').prop('disabled', true);
            $('#exampleModalLabel').text("Edit");
            $.ajax({
                    url: '{{ asset('/api/pegawai/getById') }}' + '/' + id,
                    method: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        data = data[0];
                        $('#nip').val(data.nip);
                        $('#nama').val(data.nama);
                        $('#phone').val(data.phone);
                        $('#jenis_kelamin').val(data.jenis_kelamin);
                        $('#modal').modal('toggle');
                    },
                    error: function (errormessage) {
                        alert(errormessage.responseText);
                    }
                });
        }

        function remove(id) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ asset('/api/pegawai') }}' + '/' + id,
                    method: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: "application/json; charset=utf-8",
                    success: function () {
                        $('#table').DataTable().ajax.reload();
                        swalWithBootstrapButtons.fire(
                        'Deleted!',
                        'Data deleted.',
                        'success'
                        )
                    },
                    error: function (errormessage) {
                        alert(errormessage.responseText);
                    }
                });
            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                'Cancelled',
                'Delete Cancelled :)',
                'error'
                )
            }
            })
        }

        function save() {
            if($('#nama').val() == "" || $('#phone').val() == "" || $('#nip').val() == ""){
                Swal.fire({
                        icon: 'error',
                        title: 'Please fill field!',
                        showConfirmButton: false,
                        timer: 1500
                        })

            }else if($('#jenis_kelamin').val() == "null"){
                Swal.fire({
                        icon: 'error',
                        title: 'Please select gender!',
                        showConfirmButton: false,
                        timer: 1500
                        })
            }else if($('#nip').val().length > 9){
                Swal.fire({
                        icon: 'error',
                        title: 'max nip length 10!',
                        showConfirmButton: false,
                        timer: 1500
                        })
            }else if($('#phone').val().length > 19){
                Swal.fire({
                        icon: 'error',
                        title: 'max telepon length 20!!',
                        showConfirmButton: false,
                        timer: 1500
                        })
            }else{
                if($('#exampleModalLabel').text() == "Add"){
                    var pegawai = new Object();
                    pegawai.nip = $('#nip').val();
                    pegawai.nama = $('#nama').val();
                    pegawai.phone = $('#phone').val();
                    pegawai.jenis_kelamin = $('#jenis_kelamin').val();
                    $.ajax({
                        url: '{{ asset('/api/pegawai/save') }}',
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: JSON.stringify(pegawai),
                        contentType: "application/json; charset=utf-8",
                        success: function (data) {
                            $('#table').DataTable().ajax.reload();
                            $('#modal').modal('toggle');
                            document.getElementById('insertForm').reset();
                            Swal.fire({
                            icon: 'success',
                            title: data.message+'!',
                            showConfirmButton: false,
                            timer: 1500
                            })
                        },
                        error: function (errormessage) {
                            Swal.fire({
                            icon: 'error',
                            title: errormessage.responseText+'!',
                            showConfirmButton: false,
                            timer: 1500
                            })
                        }
                    });
                }else{
                    var pegawai = new Object();
                    pegawai.nip = $('#nip').val();
                    pegawai.nama = $('#nama').val();
                    pegawai.phone = $('#phone').val();
                    pegawai.jenis_kelamin = $('#jenis_kelamin').val();
                    $.ajax({
                        url: '{{ asset('/api/pegawai/update') }}',
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: JSON.stringify(pegawai),
                        contentType: "application/json; charset=utf-8",
                        success: function () {
                            $('#table').DataTable().ajax.reload();
                            $('#modal').modal('toggle');
                            document.getElementById('insertForm').reset();
                            Swal.fire({
                            icon: 'success',
                            title: 'Data Updated!',
                            showConfirmButton: false,
                            timer: 1500
                            })
                        },
                        error: function (errormessage) {
                            alert(errormessage.responseText);
                        }
                    });
                }
            }
        }

    </script>

</body>

</html>
