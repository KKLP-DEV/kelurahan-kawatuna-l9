<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SuratMasukModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Ramsey\Uuid\Uuid;

class SuratMasukController extends Controller
{
    public function getAllData()
    {
        $data = SuratMasukModel::with('tahun', 'jenis_surat')->get();
        if ($data->isEmpty()) {
            return response()->json([
                'code' => '404',
                'message' => 'data not found',
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'success get all data',
                'data' => $data
            ]);
        }
    }

    public function getDataByTahunAndJenisSurat($id_tahun, $id_jenis_surat)
    {
        $data = SuratMasukModel::with('tahun', 'jenis_surat')
            ->where('id_tahun', $id_tahun)
            ->where('id_jenis_surat', $id_jenis_surat)
            ->get();

        return response()->json([
            'code' => 200,
            'message' => 'success get data',
            'data' => $data
        ]);
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
                'asal_surat' => 'required'
            ],
            [
                'nomor_surat.required' => 'Form nomor surat tidak boleh kosong',
                'tanggal_surat.required' => 'Form tanggal surat tidak boleh kosong',
                'tanggal_surat.date' => 'Format harus tanggal',
                'id_tahun.required' => 'Form Tahun tidak boleh kosong',
                'id_jenis_surat.required' => 'Form Jenis surat tidak boleh kosong',
                'file_surat.required' => 'Form file tidak boleh kosong',
                'file_surat.mimes' => 'File harus dalam format pdf, jpg, atau jpeg',
                'asal_surat.required' => 'Form asal surat tidak boleh kosong',
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
            $data = new SuratMasukModel;
            $data->uuid = Uuid::uuid4()->toString();
            $data->nomor_surat = $request->input('nomor_surat');
            $data->tanggal_surat = $request->input('tanggal_surat');
            $data->id_tahun = $request->input('id_tahun');
            $data->id_jenis_surat = $request->input('id_jenis_surat');
            if ($request->hasFile('file_surat')) {
                $file = $request->file('file_surat');
                $extention = $file->getClientOriginalExtension();
                $filename = 'SURAT-MASUK-' . Str::random(15) . '.' . $extention;
                Storage::makeDirectory('uploads/smasuk/');
                $file->move(public_path('uploads/smasuk/'), $filename);
                $data->file_surat = $filename;
            }
            $data->asal_surat = $request->input('asal_surat');
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
            $data = SuratMasukModel::where('uuid', $uuid)->first();
            if (!$data) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Data not found',
                ]);
            } else {
                $data->date = Carbon::createFromFormat('d F Y', $data->date)->format('Y-m-d');
                return response()->json([
                    'code' => 200,
                    'message' => 'success get data by uuid',
                    'data' => $data
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'message' => 'failed update',
                'errors' => $th->getMessage()
            ]);
        }
    }

    public function updateDataByUuid(Request $request, $uuid)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'nomor_surat' => 'required',
                'tanggal_surat' => 'required|date',
                'id_tahun' => 'required',
                'id_jenis_surat' => 'required',
                'file_surat' => 'mimes:pdf,jpg,jpeg,png,doc,docx,xls',
                'asal_surat' => 'required'
            ],
            [
                'nomor_surat.required' => 'Form nomor surat tidak boleh kosong',
                'tanggal_surat.required' => 'Form tanggal surat tidak boleh kosong',
                'tanggal_surat.date' => 'Format harus tanggal',
                'id_tahun.required' => 'Form Tahun tidak boleh kosong',
                'id_jenis_surat.required' => 'Form Jenis surat tidak boleh kosong',
                'file_surat.mimes' => 'File harus dalam format pdf, jpg, atau jpeg',
                'asal_surat.required' => 'Form asal surat tidak boleh kosong',
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
            $data = SuratMasukModel::where('uuid', $uuid)->first();
            $data->nomor_surat = $request->input('nomor_surat');
            $data->tanggal_surat = $request->input('tanggal_surat');
            $data->id_tahun = $request->input('id_tahun');
            $data->id_jenis_surat = $request->input('id_jenis_surat');
            if ($request->hasFile('file_surat')) {
                $file = $request->file('file_surat');
                $extention = $file->getClientOriginalExtension();
                $filename = 'SURAT-MASUK-' . Str::random(15) . '.' . $extention;
                Storage::makeDirectory('uploads/smasuk/');
                $file->move(public_path('uploads/smasuk/'), $filename);
                $old_file_path = public_path('uploads/smasuk/') . $data->file_surat;
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
                $data->file_surat = $filename;
            }
            $data->asal_surat = $request->input('asal_surat');
            $data->save();
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'message' => 'failed',
                'errors' => $th->getMessage()
            ]);
        }
    }
}
