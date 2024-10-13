<script>
    function Delete(id) {

        let token = $("meta[name='csrf-token']").attr("content");

        Swal.fire({
            title: 'Ingin Hapus Data?',
            text: "Data yang hilang tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: 'YA, HAPUS!'
        }).then((result) => {
            if (result.isConfirmed) {

                //fetch to delete data
                $.ajax({
                    url: "{{ route('employee.destroy') }}",
                    type: "POST",
                    cache: false,
                    data: {
                        "_token": token,
                        "id": id
                    },
                    success: function(response) {

                        //show success message
                        Swal.fire({
                            type: 'success',
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        dataTable.ajax.reload();
                    }
                });


            }
        })

    };
</script>
