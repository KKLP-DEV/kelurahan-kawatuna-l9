<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SuratKeluarModel;
use App\Models\SuratMasukModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function countData()
    {
        $suratMasuk = SuratMasukModel::count();
        $suratKeluar = SuratKeluarModel::count();

        return response()->json([
            'code' => 200,
            'message' => 'success count',
            'data' => [
                'suratMasuk' => $suratMasuk,
                'suratKeluar' => $suratKeluar
            ]
        ]);
    }
}
