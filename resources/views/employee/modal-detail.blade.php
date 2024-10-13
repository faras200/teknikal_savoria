<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="karyawanModalLabel">Detail Keluarga Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" style="padding-bottom: 0px;padding-top: 0px;">
                <div class="row mt-4">
                    <div class="col-12">
                        <label>Karyawan</label>
                        <br>
                        <div style="font-size: 20px;" id="karyawan_view"></div>
                        <hr>
                    </div>

                    <div class="col-12">
                        <label>Keluarga</label>
                        <br>
                        <table class="table table-bordered" width="100%">
                            <tr>
                                <th>Hubungan</th>
                                <th>Nama</th>
                                <th>Tanggal Lahir</th>
                            </tr>
                            <tbody id="contentnya">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script>
    function Detail(id) {

        $.ajax({
            url: "{{ route('employee.show') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            cache: false,
            success: function(response) {

                $('#karyawan_view').html(response.nama);
                $('#contentnya').empty();
                response.keluarga.forEach(function(keluarga, index) {
                    newRow = `<tr>
                        <td>${keluarga.hubungan}</td>
                        <td>${keluarga.nama}</td>
                        <td>${keluarga.tanggal_lahir}</td>
                    </tr>`
                    $('#contentnya').append(newRow);
                });

                $('#detailModal').modal('show');
            }
        });
    };
</script>
