<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\JenisSuratModel;
use App\Models\SuratKeluarModel;
use App\Models\SuratModel;


class DashboardController extends Controller
{
    public function countData()
    {
        $arsip = SuratModel::count();
        $jenisSurat = JenisSuratModel::count();

        return response()->json([
            'code' => 200,
            'message' => 'success count',
            'data' => [
                'arsip' => $arsip,
                'jenisSurat' => $jenisSurat
            ]
        ]);
    }
}
