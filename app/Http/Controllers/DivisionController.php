<?php

namespace App\Http\Controllers;

use App\Http\Requests\DivisionRequest;
use App\Http\Resources\DivisionDetailResource;
use App\Http\Resources\DivisionResource;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $divisions = Division::query();

        if ($request->name) {
            $divisions = $divisions->where('name', 'like', "%{$request->name}%");
        }

        $divisions = $divisions->paginate(5);


        return response()->json([
            "status" => "success",
            "message" => "berhasil mendapatkan data",
            "data" => [
                "divisions" => DivisionResource::collection($divisions),
                "pagination" => [
                    'total' => $divisions->total(),
                    'per_page' => $divisions->perPage(),
                    'current_page' => $divisions->currentPage(),
                    'last_page' => $divisions->lastPage(),
                    'next_page_url' => $divisions->nextPageUrl(),
                    'prev_page_url' => $divisions->previousPageUrl(),
                ]
            ]
        ], 200);
    }

    public function show(string $id)
    {
        DB::beginTransaction();

        try {
            $division = Division::find($id);

            if (!$division) {
                return response()->json(["status" => 'error', "message" => "tidak dapat menemukan divisi dengan id {$id}"], 404);
            }

            DB::commit();

            return response()->json(["status" => 'success', "message" => "berhasil mengambil data", "data" => new DivisionResource($division)], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["status" => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(DivisionRequest $request)
    {
        DB::beginTransaction();

        try {
            $division = new Division();

            $division->name = $request->name;

            $division->save();

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'berhasil menambahkan divisi baru', 'data' => new DivisionResource($division)], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["status" => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(DivisionRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $division = Division::find($id);

            if (!$division) {
                return response()->json(["status" => 'error', "message" => "tidak dapat menemukan divisi dengan id {$id}"], 404);
            }

            $division->name = $request->name;

            $division->save();

            DB::commit();

            return response()->json(["status" => 'success', "message" => "berhasil mengubah divisi"], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["status" => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $division = Division::find($id);

            if (!$division) {
                return response()->json(["status" => 'error', "message" => "tidak dapat menemukan divisi dengan id {$id}"], 404);
            }

            $division->delete();

            DB::commit();

            return response()->json(["status" => 'success', "message" => "menghapus divisi"], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["status" => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
