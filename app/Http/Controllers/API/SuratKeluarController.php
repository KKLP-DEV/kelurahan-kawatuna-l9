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
                'tujuan_surat' => 'required'
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
                'nomor_surat.unique' => 'Nomor surat sudah ada sebelumnya'
            ]
        );

        if ($validation->fails()) {
            return response () -> json([
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
}
