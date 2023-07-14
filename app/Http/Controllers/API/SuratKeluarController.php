<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SuratMasukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratKeluarController extends Controller
{
    public function getAllData()
    {

        $user = Auth::user();

        if ($user->role == 2) {
            $data = SuratMasukModel::with('tahun', 'jenis_surat', 'users')
                ->where('id_user', $user->id)
                ->get();

            return response()->json([
                'code' => 200,
                'message' => 'success get all data ',
                'data' => $data
            ]);
        } elseif ($user->role == 1) {
            $admin = SuratMasukModel::with('tahun', 'jenis_surat', 'users')->get();
            return response()->json([
                'code' => 200,
                'message' => 'success get all data',
                'data' => $admin
            ]);
        }
    }
}
