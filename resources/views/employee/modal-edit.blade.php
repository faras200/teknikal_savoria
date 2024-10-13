<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="karyawanModalLabel">Form Edit Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form Karyawan -->
                <form id="karyawanFormEdit">
                    <input type="hidden" name="id" id="idEdit">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="namaKaryawan" class="col-sm-3 col-form-label">Nama Karyawan<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="namaKaryawanEdit" name="nama_karyawan"
                                placeholder="Masukkan Nama Karyawan">
                        </div>
                        <div class="col-6">
                            <label for="tanggalLahirKaryawan" class="col-sm-3 col-form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggalLahirKaryawanEdit"
                                name="tanggal_lahir">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                            <textarea class="form-control" id="alamatEdit" name="alamat" rows="4" placeholder="Masukkan Alamat"></textarea>
                        </div>
                    </div>

                    <!-- Form Keluarga -->
                    <h5 class="mt-4">Keluarga :</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 5%">Action</th>
                                <th scope="col" style="width: 25%">Hubungan Keluarga</th>
                                <th scope="col" style="width: 35%">Nama</th>
                                <th scope="col" style="width: 35%">Tanggal Lahir</th>
                            </tr>
                        </thead>
                        <tbody id="keluargaTableBodyEdit">

                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="updateBtn">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
    //button create post event
    function Edit(id) {
        //fetch detail employee
        $.ajax({
            url: "{{ route('employee.show') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            cache: false,
            success: function(response) {

                $('#namaKaryawanEdit').val(response.nama);
                $('#alamatEdit').val(response.alamat);
                $('#idEdit').val(response.id);
                let dateParts = response.tanggal_lahir.split("-");
                let formattedDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
                $('#tanggalLahirKaryawanEdit').val(formattedDate);

                $('#keluargaTableBodyEdit').empty(); // Kosongkan dulu isi table
                response.keluarga.forEach(function(keluarga, index) {
                    var button = index === 0 ?
                        `<button type="button" class="btn btn-outline-primary" onclick="addEditRow()">+</button>` :
                        `<button type="button" class="btn btn-outline-danger removeRowBtn">-</button>`;

                    var newRow = `
                        <tr>
                            <td>${button}</td>
                            <td>
                                <select class="form-select" name="hubungan_keluarga[]">
                                    <option ${keluarga.hubungan == 'Ibu' ? 'selected' : ''} value="Ibu">Ibu</option>
                                    <option ${keluarga.hubungan == 'Ayah' ? 'selected' : ''} value="Ayah">Ayah</option>
                                    <option ${keluarga.hubungan == 'Istri' ? 'selected' : ''} value="Istri">Istri</option>
                                    <option ${keluarga.hubungan == 'Anak' ? 'selected' : ''} value="Anak">Anak</option>
                                    <option ${keluarga.hubungan == 'Saudara' ? 'selected' : ''} value="Saudara">Saudara</option>
                                </select>
                            </td>
                            <td><input type="text" class="form-control" name="nama_keluarga[]" value="${keluarga.nama}"></td>
                            <td><input type="date" class="form-control" name="tanggal_lahir_keluarga[]" value="${keluarga.tanggal_lahir}"></td>
                        </tr>`;

                    $('#keluargaTableBodyEdit').append(newRow);
                });


                $('#editModal').modal('show');
            }
        });
    };

    //action update post
    $('#updateBtn').click(function() {

        var formData = $('#karyawanFormEdit').serializeArray();
        $.ajax({
            url: "{{ route('employee.update') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            success: function(response) {
                $('#editModal').modal('hide'); // Tutup modal
                dataTable.ajax.reload();
                Swal.fire({
                    type: 'success',
                    icon: 'success',
                    title: `${response.message}`,
                    showConfirmButton: false,
                    timer: 2000
                });
            },
            error: function(error) {
                // Jika terjadi error
                Swal.fire({
                    type: 'error',
                    icon: 'error',
                    title: 'Terjadi Kesalahan, Coba Lagi Nanti!!',
                    showConfirmButton: false,
                    timer: 2000
                });
                console.log(error); // Lihat error dari server
            }
        });

    });

    // Function to add a new row
    function addEditRow() {
        var newRow = `
                    <tr>
                        <td><button type="button" class="btn btn-outline-danger removeRowBtn" >-</button></td>
                        <td>
                            <select class="form-select" name="hubungan_keluarga[]">
                                <option selected>Pilih Hubungan</option>
                                <option value="Ibu">Ibu</option>
                                <option value="Ayah">Ayah</option>
                                <option value="Istri">Istri</option>
                                <option value="Anak">Anak</option>
                                <option value="Saudara">Saudara</option>
                            </select>
                        </td>
                        <td><input type="text" class="form-control" name="nama_keluarga[]" placeholder="Masukkan Nama"></td>
                        <td><input type="date" class="form-control" name="tanggal_lahir_keluarga[]"></td>
                    </tr>`;
        $('#keluargaTableBodyEdit').append(newRow);

    };
</script>
