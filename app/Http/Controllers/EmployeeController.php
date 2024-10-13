<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\FamilyEmployee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    //

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = Employee::select(
                'm_employee.*',
                'm_family_employee.*',
                DB::raw('DATE_FORMAT(m_employee.tanggal_lahir, "%d %b %Y") AS tanggal_lahir'),
            )
                ->join('m_family_employee', 'm_family_employee.m_employee_id', '=', 'm_employee.m_employee_id')
                ->groupBy('m_employee.m_employee_id')
                ->get();

            return Datatables::of($data)->addIndexColumn()->make(true);
        }

        return view('employee.index');
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            // Validasi data
            $request->validate([
                'nama_karyawan' => 'required|string',
                'tanggal_lahir' => 'nullable|date',
                'alamat' => 'nullable|string',
                'hubungan_keluarga.*' => 'nullable|string',
                'nama_keluarga.*' => 'nullable|string',
                'tanggal_lahir_keluarga.*' => 'nullable|date',
            ]);

            // Simpan data karyawan dan keluarga ke database
            $employee = Employee::create([
                'nama_karyawan' => $request->nama_karyawan,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'email' => strtolower(str_replace(' ', '', $request->nama_karyawan)) . '@domain.com',
                'valid_form' =>  date('Y-m-d'),
                'create_by' => $user->id,
                'create_date' => date('Y-m-d H:i:s'),
            ]);

            foreach ($request->hubungan_keluarga as $index => $hubungan) {
                FamilyEmployee::create([
                    'm_employee_id' => $employee->id,
                    'hubungan_keluarga' => $hubungan,
                    'nama_anggota_keluarga' => $request->nama_keluarga[$index],
                    'tanggal_lahir_anggota_keluarga' => $request->tanggal_lahir_keluarga[$index],
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Data Berhasil Disimpan!']);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle error saat query ke database gagal
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Handle error umum lainnya
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }


    public function show(Request $request)
    {
        $id = $request->id;
        // Mengambil data sales planning beserta detailnya menggunakan query builder
        $employee = Employee::select(
            'm_employee.*',
            'm_family_employee.*',
            DB::raw('DATE_FORMAT(m_employee.tanggal_lahir, "%d-%m-%Y") AS tanggal_lahir'),
        )
            ->join('m_family_employee', 'm_family_employee.m_employee_id', '=', 'm_employee.m_employee_id')
            ->where('m_employee.m_employee_id', $id)
            ->get();


        // Memeriksa apakah data ditemukan
        if ($employee->isEmpty()) {
            return response()->json([
                'message' => 'Employee not found.'
            ], 404);
        }

        // Mengelompokkan data employee dan detail keluarga
        $employeeData = [
            'id' => $employee[0]->m_employee_id,
            'nama' => $employee[0]->nama_karyawan,
            'email' => $employee[0]->email,
            'tanggal_lahir' => $employee[0]->tanggal_lahir,
            'alamat' => $employee[0]->alamat,
            'keluarga' => []
        ];

        foreach ($employee as $keluarga) {
            $employeeData['keluarga'][] = [
                'hubungan' => $keluarga->hubungan_keluarga,
                'nama' => $keluarga->nama_anggota_keluarga,
                'tanggal_lahir' => $keluarga->tanggal_lahir_anggota_keluarga
            ];
        }

        // Mengembalikan data dalam format JSON
        return response()->json($employeeData, 200);
    }

    public function update(Request $request, Employee $post)
    {
        try {
            $user = Auth::user();
            $id = $request->input('id');
            // Validasi data
            $request->validate([
                'nama_karyawan' => 'required|string',
                'tanggal_lahir' => 'nullable|date',
                'alamat' => 'nullable|string',
                'hubungan_keluarga.*' => 'nullable|string',
                'nama_keluarga.*' => 'nullable|string',
                'tanggal_lahir_keluarga.*' => 'nullable|date',
            ]);

            Employee::where('m_employee_id', $id)->update([
                'nama_karyawan' => $request->input('nama_karyawan'),
                'tanggal_lahir' => $request->input('tanggal_lahir'),
                'alamat' => $request->input('alamat'),
                'updated_by' => $user->id,
                'updated_date' => date('Y-m-d'),
            ]);

            FamilyEmployee::where('m_employee_id', $id)->delete();

            foreach ($request->hubungan_keluarga as $index => $hubungan) {
                FamilyEmployee::create([
                    'm_employee_id' => $id,
                    'hubungan_keluarga' => $hubungan,
                    'nama_anggota_keluarga' => $request->nama_keluarga[$index],
                    'tanggal_lahir_anggota_keluarga' => $request->tanggal_lahir_keluarga[$index],
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Data Berhasil Disimpan!']);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle error saat query ke database gagal
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Handle error umum lainnya
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        //delete post by ID
        Employee::where('m_employee_id', $request->id)->delete();
        FamilyEmployee::where('m_employee_id', $request->id)->delete();

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Karyawan Berhasil Dihapus!.',
        ]);
    }
}
