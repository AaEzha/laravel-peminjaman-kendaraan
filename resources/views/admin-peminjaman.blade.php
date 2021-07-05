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
                        <h1>Data Peminjaman Kendaraan</h1>
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
                                            <th scope="col">Nama Kendaraan</th>
                                            <th scope="col">Nama Pegawai</th>
                                            <th scope="col">Telepon Pegawai</th>
                                            <th scope="col">Alasan</th>
                                            <th scope="col">Tanggal Pinjam</th>
                                            <th scope="col">Tanggal Kembali</th>
                                            <th scope="col" style="width: 8%">Option</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div th:fragment="modal" class="modal fade bd-example-modal-lg" id="modal" tabindex="-1"
                        role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
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
                                                    <label>Pegawai</label>
                                                      <input type="hidden" id="id" class="form-control p_input" required>
                                                      <select class="form-control" id="pegawai">
                                                          <option value="">-- Pilih --</option>
                                                                @foreach ($pegawai as $p)
                                                                <option value="{{$p->nip}}">{{$p->nama}}</option>
                                                                @endforeach
                                                                <option value="tambah">-- Tambah Baru --</option>
                                                        <select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Aalsan Pinjam</label>
                                                      <input type="text" id="alasan" class="form-control p_input" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Kendaraan</label>
                                                    <select class="form-control" id="kendaraan">
                                                        <option value="">-- Pilih --</option>
                                                        @foreach ($kendaraan as $k)
                                                            <option value="{{$k->id}}">{{$k->nama}}</option>
                                                            @endforeach
                                                            <option value="tambah">-- Tambah Baru --</option>
                                                      <select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal Pinjam</label>
                                                      <input type="date" id="tanggal_pinjam" class="form-control p_input" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal Kembali</label>
                                                      <input type="date" id="tanggal_kembali" class="form-control p_input" required>
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
                url: '{{ asset('/api/peminjaman/getAll') }}',
            },
            columns: [
                {data: null, name: null, orderable: false},
                {data: 'kendaraan.nama', name: 'kendaraan'},
                {data: 'pegawai.nama', name: 'pegawai'},
                {data: 'pegawai.phone', name: 'telepon'},
                {data: 'alasan', name: 'alasan'},
                {data: 'tanggal_pinjam', name: 'tanggal_pinjam'},
                {data: 'tanggal_kembali', name: 'tanggal_kembali'},
                {data: 'id', name: 'id',
                    render :function(data, type, row, meta) {
                    return type === 'display' ?
                        `<button onclick="selesai('${data}')" class="btn btn-sm btn-primary text-white">Selesai <span class="fa fa-check"></span></button>` :
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
            document.getElementById('insertForm').reset();
        }

        function selesai(id) {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
            title: 'Yakin?',
            text: "menyelesaikan peminjaman!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            reverseButtons: true
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ asset('/api/peminjaman') }}' + '/' + id,
                    method: "PUT",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: "application/json; charset=utf-8",
                    success: function (data) {
                        alert(data)
                        $('#table').DataTable().ajax.reload();
                        swalWithBootstrapButtons.fire(
                        'Berhasil!',
                        'peminjaman kendaraan selesai',
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
                'Proses Cancelled :)',
                'error'
                )
            }
            })
        }

        function save() {
            if($('#pegawai').val() == "" || $('#kendaraan').val() == ""
            || $('#tanggal_pinjam').val() == "" || $('#tanggal_kembali').val() == "" || $('#alasan').val() == ""){
                Swal.fire({
                        icon: 'error',
                        title: 'Please fill field!',
                        showConfirmButton: false,
                        timer: 1500
                        })
            }else if($('#tanggal_pinjam').val() > $('#tanggal_kembali').val()){
                Swal.fire({
                        icon: 'error',
                        title: 'Tanggal pinjam tidak boleh melewati tanggal kembali!',
                        showConfirmButton: false,
                        timer: 1500
                        })
            }else{
                var peminjaman = new Object();
                peminjaman.id = 0;
                peminjaman.pegawai = $('#pegawai').val();
                peminjaman.kendaraan = $('#kendaraan').val();
                peminjaman.tanggal_pinjam = $('#tanggal_pinjam').val();
                peminjaman.tanggal_kembali = $('#tanggal_kembali').val();
                peminjaman.alasan = $('#alasan').val();
                console.log(peminjaman)
                $.ajax({
                    url: '{{ asset('/api/peminjaman/save') }}',
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify(peminjaman),
                    contentType: "application/json; charset=utf-8",
                    success: function () {
                        $('#table').DataTable().ajax.reload();
                        $('#modal').modal('toggle');
                        document.getElementById('insertForm').reset();
                        Swal.fire({
                        icon: 'success',
                        title: 'Data added!',
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

        var dtToday = new Date();

        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();

        var maxDate = year + '-' + month + '-' + day;
        $('#tanggal_pinjam').attr('min', maxDate);
        $('#tanggal_kembali').attr('min', maxDate);

        $('#pegawai').on('change', function() {
            if(this.value == "tambah"){
                window.location.replace('{{ asset('/data-pegawai') }}');
            }
        });

        $('#kendaraan').on('change', function() {
            if(this.value == "tambah"){
                window.location.replace('{{ asset('/data-kendaraan') }}');
            }
        });

    </script>

</body>

</html>
