<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TahunModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class TahunController extends Controller
{
    public function getAllData()
    {
        $data = TahunModel::all();
        if ($data->isEmpty()) {
            return response()->json([
                'code' => 404,
                'message' => 'Data not found'
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'success get all data',
                'data' => $data
            ]);
        }
    }

    public function createData(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'tahun' => 'required|numeric|min:2023|max:2028|unique:tb_tahun'
            ],
            [
                'tahun.required' => 'Form tahun tidak boleh kosong',
                'tahun.numeric' => 'Form tahun harus berupa angka',
                'tahun.min' => 'Tahun minimal 2023',
                'tahun.max' => 'Tahun maksimal 2028',
                'tahun.unique' => 'Tahun sudah pernah diinput sebelumnya'
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
            $data = new TahunModel;
            $data->uuid = Uuid::uuid4()->toString();
            $data->tahun = $request->input('tahun');
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
            'message' => 'succes create data',
            'data' => $data
        ]);
    }

    public function getDataByUuid($uuid)
    {
        if (!Uuid::isValid($uuid)) {
            return response()->json([
                'code' => 404,
                'message' => 'Uuid invalid '
            ]);
        }

        $data = TahunModel::where('uuid', $uuid)->first();
        if (!$data) {
            return response()->json([
                'code' => 404,
                'message' => 'data not found',
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'success get data by uuid',
                'data' => $data
            ]);
        }
    }

    public function updateDataByUuid(Request $request, $uuid)
    {

        if (!Uuid::isValid($uuid)) {
            return response()->json([
                'code' => 404,
                'message' => 'Uuid invalid '
            ]);
        }

        $validation = Validator::make(
            $request->all(),
            [
                'tahun' => 'required|numeric|min:2023|max:2028|unique:tb_tahun'
            ],
            [
                'tahun.required' => 'Form tahun tidak boleh kosong',
                'tahun.numeric' => 'Form tahun harus berupa angka',
                'tahun.min' => 'Tahun minimal 2023',
                'tahun.max' => 'Tahun maksimal 2028',
                'tahun.unique' => 'Tahun sudah pernah diinput sebelumnya'
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
            $data = TahunModel::where('uuid', $uuid)->first();
            $data->tahun = $request->input('tahun');
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

    public function deleteData($uuid)
    {
        if (!Uuid::isValid($uuid)) {
            return response()->json([
                'code' => 404,
                'message' => 'Uuid invalid '
            ]);
        }

        try {
            $data = TahunModel::where('uuid', $uuid)->first();
            if (!$data) {
                return response()->json([
                    'code' => 404,
                    'message' => 'data not found'
                ]);
            }
            $data->delete();
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
