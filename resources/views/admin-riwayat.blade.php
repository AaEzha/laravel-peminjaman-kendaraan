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
                        <h1>Data Riwayat Peminjaman Kendaraan</h1>
                    </div>
                    <div class="section-body">
                        <div class="card">
                            <div class="card-body">
                                <table id="table" class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 5%">No</th>
                                            <th scope="col">Nama Kendaraan</th>
                                            <th scope="col">Nama Pegawai</th>
                                            <th scope="col">Tanggal Pinjam</th>
                                            <th scope="col">Tanggal Kembali</th>
                                            <th scope="col">Tanggal Dikembalikan</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Denda</th>
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
                url: '{{ asset('/api/history/getAll') }}',
            },
            columns: [
                {data: null, name: null, orderable: false},
                {data: 'kendaraan.nama', name: 'kendaraan'},
                {data: 'pegawai.nama', name: 'pegawai'},
                {data: 'tanggal_pinjam', name: 'tanggal_pinjam'},
                {data: 'tanggal_kembali', name: 'tanggal_kembali'},
                {data: 'tanggal_dikembalikan', name: 'tanggal_dikembalikan'},
                {data: 'denda', name: 'denda',
                    render :function(data, type, row, meta) {
                        if(data == 0){
                            return type === 'display' ?
                                `<span class="badge badge-success">Tidak Terlambat</span>` :
                                data;
                        }else{
                            return type === 'display' ?
                                `<span class="badge badge-danger">Terlambat (${getDayFromDate(row.tanggal_dikembalikan, row.tanggal_kembali)} Hari)</span>` :
                                data;
                        }
                    }
                },
                {data: 'denda', name: 'denda',
                    render :function(data, type, row, meta) {
                        if(data == 0){
                            return type === 'display' ?
                                `-` :
                                data;
                        }else{
                            return type === 'display' ?
                                formatRupiah(''+data+'', 'Rp. ') :
                                data;
                        }
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
            document.getElementById('insertForm').reset();
        }

        function edit(id) {
            $('#nip').prop('disabled', true);
            $.ajax({
                    url: '{{ asset('/api/history/getById') }}' + '/' + id,
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
                    url: '{{ asset('/api/history') }}' + '/' + id,
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
            }else{
                var peminjaman = new Object();
                peminjaman.nip = $('#nip').val();
                peminjaman.nama = $('#nama').val();
                peminjaman.phone = $('#phone').val();
                peminjaman.jenis_kelamin = $('#jenis_kelamin').val();
                $.ajax({
                    url: '{{ asset('/api/history/save') }}',
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

        /* Fungsi formatRupiah */
		function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}

			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}

        function getDayFromDate(tanggalDikembalikan, tanggalKembali){
            tanggalKembali = new Date(tanggalKembali);
            tanggalDikembalikan = new Date(tanggalDikembalikan);
            let hours = hoursDiff(tanggalKembali, tanggalDikembalikan);
            let daysDiff = Math.floor( hours / 24 );
            return daysDiff;
        }

        function hoursDiff(d1, d2) {
            let minutes = minutesDiff(d1, d2);
            let hoursDiff = Math.floor( minutes / 60 );
            return hoursDiff;
        }

        function minutesDiff(d1, d2) {
            let seconds = secondsDiff(d1, d2);
            let minutesDiff = Math.floor( seconds / 60 );
            return minutesDiff;
        }

        function secondsDiff(d1, d2) {
            let millisecondDiff = d2 - d1;
            let secDiff = Math.floor( ( d2 - d1) / 1000 );
            return secDiff;
        }
    </script>

</body>

</html>
