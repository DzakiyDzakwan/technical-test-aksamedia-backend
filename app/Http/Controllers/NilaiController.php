<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function nilaiRT()
    {

        $data = Nilai::select('nama', 'nisn')->groupBy('nama', 'nisn');

        $data = $data->get();

        $data = $data->map(function ($group) {

            $items = Nilai::where('nisn', $group->nisn)->where('materi_uji_id', 7)->whereNot('nama_pelajaran', 'Pelajaran Khusus')->get();

            return [
                "nama" => $group->nama,
                "nilaiRt" => [
                    "artistic" => $items->where('nama_pelajaran', 'ARTISTIC')->first()->skor,
                    "conventional" => $items->where('nama_pelajaran', 'CONVENTIONAL')->first()->skor ?? 0,
                    "enterprising" => $items->where('nama_pelajaran', 'ENTERPRISING')->first()->skor ?? 0,
                    "investigative" => $items->where('nama_pelajaran', 'INVESTIGATIVE')->first()->skor ?? 0,
                    "realistic" => $items->where('nama_pelajaran', 'REALISTIC')->first()->skor ?? 0,
                    "social" => $items->where('nama_pelajaran', 'SOCIAL')->first()->skor ?? 0
                ],
                "nisn" => $group->nisn,
            ];
        });

        return response()->json($data);
    }

    public function nilaiST()
    {

        $data = DB::table('nilai')
            ->where('materi_uji_id', 4)
            ->select(
                'nama',
                'nisn',
                DB::raw('
            SUM(CASE pelajaran_id
                WHEN 44 THEN skor * 41.67
                WHEN 45 THEN skor * 29.67
                WHEN 46 THEN skor * 100
                WHEN 47 THEN skor * 23.81
                ELSE 0
            END) AS total
        ')
            )
            ->groupBy('nama', 'nisn')
            ->orderBy('total', 'desc')
            ->get();

        $data = $data->map(function ($group) {

            $items = Nilai::where('nisn', $group->nisn)->select('pelajaran_id', 'nama_pelajaran', DB::raw('
            (CASE pelajaran_id
                WHEN 44 THEN skor * 41.67
                WHEN 45 THEN skor * 29.67
                WHEN 46 THEN skor * 100
                WHEN 47 THEN skor * 23.81
                ELSE 0
            END) AS skor
            '))->where('materi_uji_id', 4)->get();

            return [
                "listNilai" => [
                    "figural" => $items->where('pelajaran_id', 47)->first()->skor,
                    "kuantitatif" => $items->where('pelajaran_id', 45)->first()->skor,
                    "penalaran" => $items->where('pelajaran_id', 46)->first()->skor,
                    "verbal" => $items->where('pelajaran_id', 44)->first()->skor,
                ],
                "nama" => $group->nama,
                "nisn" => $group->nisn,
                "total" => $group->total,
            ];
        });


        return response()->json($data);
    }
}
