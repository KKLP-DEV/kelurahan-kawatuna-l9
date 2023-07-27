<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SuratKeluarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class SuratKeluarController extends Controller
{
    public function getAllData()
    {
        try {
            $user = Auth::user();

            if ($user->role == 2) {
                $data = SuratKeluarModel::with('tahun', 'jenis_surat', 'users')
                    ->where('id_user', $user->id)
                    ->get();

                return response()->json([
                    'code' => 200,
                    'message' => 'success get all data ',
                    'data' => $data
                ]);
            } elseif ($user->role == 1) {
                $admin = SuratKeluarModel::with('tahun', 'jenis_surat', 'users')->get();
                return response()->json([
                    'code' => 200,
                    'message' => 'success get all data',
                    'data' => $admin
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => $th->getMessage()
            ]);
        }
    }

    public function createData(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'nomor_surat' => 'required',
                'tanggal_surat' => 'required|date',
                'id_tahun' => 'required',
                'id_jenis_surat' => 'required',
                'file_surat' => 'required|mimes:pdf,jpg,jpeg,png,doc,docx,xls',
                'tujuan_surat' => 'required',
                'perihal' => 'required'
            ],
            [
                'nomor_surat.required' => 'Form nomor surat tidak boleh kosong',
                'tanggal_surat.required' => 'Form tanggal surat tidak boleh kosong',
                'tanggal_surat.date' => 'Format harus tanggal',
                'id_tahun.required' => 'Form Tahun tidak boleh kosong',
                'id_jenis_surat.required' => 'Form Jenis surat tidak boleh kosong',
                'file_surat.required' => 'Form file tidak boleh kosong',
                'file_surat.mimes' => 'File harus dalam format pdf, jpg, atau jpeg',
                'tujuan_surat.required' => 'Form asal surat tidak boleh kosong',
                'nomor_surat.unique' => 'Nomor surat sudah ada sebelumnya',
                'perihal.required' => 'Form perihal tidak boleh kosong',
            ]
        );

        if ($validation->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'check your validation',
                'errors' => $validation->errors()
            ]);
        }

        try {
            $user = Auth::user();
            $data = new SuratKeluarModel;
            $data->uuid = Uuid::uuid4()->toString();
            $data->id_user = $user->id;
            $data->nomor_surat = $request->input('nomor_surat');
            $data->tanggal_surat = $request->input('tanggal_surat');
            $data->id_tahun = $request->input('id_tahun');
            $data->id_jenis_surat = $request->input('id_jenis_surat');
            if ($request->hasFile('file_surat')) {
                $file = $request->file('file_surat');
                $extention = $file->getClientOriginalExtension();
                $filename = 'SURAT-KELUAR-' . Str::random(15) . '.' . $extention;
                Storage::makeDirectory('uploads/skeluar/');
                $file->move(public_path('uploads/skeluar/'), $filename);
                $data->file_surat = $filename;
            }
            $data->tujuan_surat = $request->input('tujuan_surat');
            $data->perihal = $request->input('perihal');
            $data->save();
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'message' => 'Failed',
                'errors' => $th->getMessage()
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success upload data',
            'data' => $data
        ]);
    }

    public function getDataByUuid($uuid)
    {
        if (!Uuid::isValid($uuid)) {
            return response()->json([
                'code' => 404,
                'message' => 'UUID Invalid'
            ]);
        }
        try {
            $data = SuratKeluarModel::where('uuid', $uuid)->first();
            if (!$data) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Data not found'
                ]);
            } else {
                $data->tanggal_surat = Carbon::createFromFormat('d F Y', $data->tanggal_surat)->format('Y-m-d');
                return response()->json([
                    'code' => 200,
                    'message' => 'success get data by uuid',
                    'data' => $data
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'message' => 'failed',
                'errors' => $th->getMessage()
            ]);
        }
    }

    public function updateDataByUuid(Request $request , $uuid)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'nomor_surat' => 'required',
                'tanggal_surat' => 'required|date',
                'id_tahun' => 'required',
                'id_jenis_surat' => 'required',
                'file_surat' => 'required|mimes:pdf,jpg,jpeg,png,doc,docx,xls',
                'tujuan_surat' => 'required',
                'perihal' => 'required'
            ],
            [
                'nomor_surat.required' => 'Form nomor surat tidak boleh kosong',
                'tanggal_surat.required' => 'Form tanggal surat tidak boleh kosong',
                'tanggal_surat.date' => 'Format harus tanggal',
                'id_tahun.required' => 'Form Tahun tidak boleh kosong',
                'id_jenis_surat.required' => 'Form Jenis surat tidak boleh kosong',
                'file_surat.required' => 'Form file tidak boleh kosong',
                'file_surat.mimes' => 'File harus dalam format pdf, jpg, atau jpeg',
                'tujuan_surat.required' => 'Form asal surat tidak boleh kosong',
                'nomor_surat.unique' => 'Nomor surat sudah ada sebelumnya',
                'perihal.required' => 'Form perihal tidak boleh kosong',
            ]
        );

        if ($validation->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'check your validation',
                'errors' => $validation->errors()
            ]);
        }

        try {
            $user = Auth::user();
            $data = SuratKeluarModel::where('uuid', $uuid)->first();
            $data->id_user = $user->id;
            $data->nomor_surat = $request->input('nomor_surat');
            $data->tanggal_surat = $request->input('tanggal_surat');
            $data->id_tahun = $request->input('id_tahun');
            $data->id_jenis_surat = $request->input('id_jenis_surat');
            if ($request->hasFile('file_surat')) {
                $file = $request->file('file_surat');
                $extention = $file->getClientOriginalExtension();
                $filename = 'SURAT-KELUAR-' . Str::random(15) . '.' . $extention;
                Storage::makeDirectory('uploads/skeluar/');
                $file->move(public_path('uploads/skeluar/'), $filename);
                $old_file_path = public_path('uploads/skeluar/') . $data->file_surat;
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
                $data->file_surat = $filename;
            }
            $data->tujuan_surat = $request->input('tujuan_surat');
            $data->perihal = $request->input('perihal');
            $data->save();
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'message' => 'failed',
                'errors' => $th->getMessage()
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success update data',
            'data' => $data
        ]);
    }


    public function getDataByUser($id_tahun, $id_jenis_surat)
    {
        $user = Auth::user();

        if ($user->role == 2) {
            $data = SuratKeluarModel::with('tahun', 'jenis_surat', 'users')
                ->where('id_tahun', $id_tahun)
                ->where('id_jenis_surat', $id_jenis_surat)
                ->where('id_user', $user->id)
                ->get();

            return response()->json([
                'code' => 200,
                'message' => 'success get data',
                'data' => $data
            ]);
        } elseif ($user->role == 1) {
            $admin = SuratKeluarModel::with('tahun', 'jenis_surat', 'users')
                ->where('id_tahun', $id_tahun)
                ->where('id_jenis_surat', $id_jenis_surat)
                ->get();

            return response()->json([
                'code' => 200,
                'message' => 'success get data',
                'data' => $admin
            ]);
        }
    }

    public function deleteData($uuid)
    {
        if (!Uuid::isValid($uuid)) {
            return response()->json([
                'code' => 404,
                'message' => 'UUID Invalid'
            ]);
        }

        try {
            $data = SuratKeluarModel::where('uuid', $uuid)->first();
            if (!$data) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Data not found'
                ]);
            }
            $location = 'uploads/smasuk/' . $data->file_surat;
            $data->delete();

            if (File::exists($location)) {
                File::delete($location);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'message' => 'failed delete data',
                'errors' => $th->getMessage()
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success delete data'
        ]);
    }

}
