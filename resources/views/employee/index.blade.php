<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="container" style="margin-top: 50px">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card border-0 rounded-md ">
                                <div class="card-body">

                                    <a href="javascript:void(0)" class="btn btn-success mb-2"
                                        id="btn-create-karyawan">Tambah Data</a>
                                    <div class="table-responsive">
                                        <table id="table-karyawan"
                                            class="table table-flush table-bordered dt-responsive nowrap"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th style="width: 3%">No</th>
                                                    <th style="width: 15%">Nama</th>
                                                    <th style="width: 15%">Email</th>
                                                    <th>Alamat</th>
                                                    <th style="width: 15%;">Tanggal Lahir</th>
                                                    <th style="width: 15%;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @push('scripts')
                    <script type="text/javascript">
                        var dataTable = "";
                        $(document).ready(function() {
                            dataTable = $('#table-karyawan').DataTable({
                                processing: true,
                                serverSide: true,
                                ajax: '{{ url()->current() }}',
                                oLanguage: {
                                    "sEmptyTable": "Belum ada data",
                                    "sLengthMenu": "_MENU_ Tampilkan data",
                                    "sZeroRecords": "Tidak ditemukan data yang sesuai",
                                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                                    "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                                    "sInfoFiltered": "(disaring dari total _MAX_ data)",
                                },
                                lengthMenu: [
                                    [25, 50, 100, 500, ],
                                    [25, 50, 100, 500, ]
                                ],

                                pagingType: 'simple_numbers',
                                columns: [{
                                        data: 'DT_RowIndex',
                                        name: 'DT_RowIndex',
                                        className: 'text-center'
                                    },
                                    {
                                        data: 'nama_karyawan',
                                        name: 'nama_karyawan'
                                    },
                                    {
                                        data: 'email',
                                        name: 'email'
                                    },
                                    {
                                        data: 'alamat',
                                        name: 'alamat'
                                    },
                                    {
                                        data: 'tanggal_lahir',
                                        name: 'tanggal_lahir'
                                    },
                                    {
                                        render: function(data, type, row) {

                                            return '&nbsp;<button class="btn btn-success btn-sm" onclick="Detail(' +
                                                row
                                                .m_employee_id +
                                                ')"><i class="fa fa-eye"></i></button>&nbsp;&nbsp;<button class="btn btn-warning btn-sm" onclick="Edit(' +
                                                row
                                                .m_employee_id +
                                                ')"><i class="fa fa-edit"></i></button>&nbsp;&nbsp;<button class="btn btn-danger btn-sm" onclick="Delete(' +
                                                row
                                                .m_employee_id +
                                                ')"><i class="fa fa-trash"></i></button>';

                                        }

                                    }

                                ]
                            });
                        });
                    </script>
                @endpush
                @include('employee.modal-create')
                @include('employee.modal-edit')
                @include('employee.modal-detail')
                @include('employee.delete-post')

            </div>
        </div>
    </div>

</x-app-layout>
