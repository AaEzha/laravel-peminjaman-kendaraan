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
                        <h1>Data Kendaraan</h1>
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
                                            <th scope="col">Nama</th>
                                            <th scope="col">Plat Nomor</th>
                                            <th scope="col" style="width: 10%">Status</th>
                                            <th scope="col" style="width: 10%">Option</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div th:fragment="modal" class="modal fade bd-example-modal-lg" id="modal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Data</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="insertForm" class="px-4">
                                    <div class="modal-body">
                                        <div class="form-group justify-content-between row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Nama</label>
                                                      <input type="hidden" id="id" class="form-control p_input">
                                                      <input type="text" id="nama" class="form-control p_input" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Plat Nomor</label>
                                                      <input type="text" id="plat_nomor" class="form-control p_input" required>
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
                url: '{{ asset('/api/kendaraan/getAll') }}',
            },
            columns: [
                {data: null, name: null, orderable: false},
                {data: 'nama', name: 'nama'},
                {data: 'plat_nomor', name: 'plat_nomor'},
                {data: 'is_used', name: 'is_used',
                    render :function(data, type, row, meta) {
                        if(data == false){
                            return type === 'display' ?
                                `<span class="badge badge-success">Tersedia</span>` :
                                data;
                        }else{
                            return type === 'display' ?
                                `<span class="badge badge-danger">Dipinjam</span>` :
                                data;
                        }
                    }
                },
                {data: 'id', name: 'id',
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
            $('#id').val("")
            $('#exampleModalLabel').text("Add");
            document.getElementById('insertForm').reset();
        }

        function edit(id) {
            $('#exampleModalLabel').text("Edit");
            $.ajax({
                    url: '{{ asset('/api/kendaraan/getById') }}' + '/' + id,
                    method: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        console.log(data.id)
                        $('#id').val(data.id);
                        $('#nama').val(data.nama);
                        $('#plat_nomor').val(data.plat_nomor);
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
                    url: '{{ asset('/api/kendaraan') }}' + '/' + id,
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
            if($('#plat_nomor').val() == "" || $('#nama').val() == ""){
                Swal.fire({
                        icon: 'error',
                        title: 'Please fill field!',
                        showConfirmButton: false,
                        timer: 1500
                        })
            }else{
                var kendaraan = new Object();
                kendaraan.id = $('#id').val();
                kendaraan.nama = $('#nama').val();
                kendaraan.plat_nomor = $('#plat_nomor').val();
                $.ajax({
                    url: '{{ asset('/api/kendaraan/save') }}',
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify(kendaraan),
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        $('#table').DataTable().ajax.reload();
                        $('#modal').modal('toggle');
                        document.getElementById('insertForm').reset();
                        Swal.fire({
                        icon: 'success',
                        title: data.message,
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

    </script>

</body>

</html>
