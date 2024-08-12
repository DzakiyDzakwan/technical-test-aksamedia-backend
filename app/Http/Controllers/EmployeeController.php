<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    use FileTrait;

    public function index(Request $request)
    {
        $employees = Employee::query()->with('division');

        if ($request->name) {
            $employees = $employees->where('name', 'like', "%{$request->name}%");
        }

        if ($request->division) {
            $employees = $employees->where('division_id', $request->division);
        }

        $employees = $employees->paginate(5);

        return response()->json([
            "status" => "success",
            "message" => "berhasil mendapatkan data",
            "data" => [
                "employees" => EmployeeResource::collection($employees),
                "pagination" => [
                    'total' => $employees->total(),
                    'per_page' => $employees->perPage(),
                    'current_page' => $employees->currentPage(),
                    'last_page' => $employees->lastPage(),
                    'next_page_url' => $employees->nextPageUrl(),
                    'prev_page_url' => $employees->previousPageUrl(),
                ]
            ]
        ], 200);
    }

    public function show(string $id)
    {
        DB::beginTransaction();

        try {
            $employee = Employee::find($id);

            if (!$employee) {
                return response()->json(["status" => 'error', "message" => "tidak dapat menemukan pegawai dengan id {$id}"], 404);
            }

            DB::commit();

            return response()->json(["status" => 'success', "message" => "berhasil mengambil data", "data" => new EmployeeResource($employee)], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["status" => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(EmployeeStoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $uuid = Str::uuid();
            $employee = new Employee();

            $employee->uuid = $uuid;
            $employee->name = $request->name;
            $employee->phone = $request->phone;
            $employee->division_id = $request->division;
            $employee->position = $request->position;
            $employee->image = $this->saveImage($request->file()["image"], $uuid);

            $employee->save();

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'berhasil menambahkan pegawai baru', 'data' => new EmployeeResource($employee)], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["status" => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(EmployeeUpdateRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $employee = Employee::find($id);

            if (!$employee) {
                return response()->json(["status" => 'error', "message" => "tidak dapat menemukan pegawai dengan id {$id}"], 404);
            }

            if ($request->name) {
                $employee->name = $request->name;
            }

            if ($request->phone) {
                $employee->phone = $request->phone;
            }

            if ($request->division) {
                $employee->division_id = $request->division;
            }

            if ($request->position) {
                $employee->position = $request->position;
            }

            if (isset($request->file()["image"])) {
                $employee->image = $this->saveImage($request->file()["image"], $employee->uuid);
            }
            $employee->save();

            DB::commit();

            return response()->json(["status" => 'success', "message" => "berhasil mengubah data pegawai"], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["status" => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $employee = Employee::find($id);

            if (!$employee) {
                return response()->json(["status" => 'error', "message" => "tidak dapat menemukan pegawai dengan id {$id}"], 404);
            }

            $employee->delete();

            DB::commit();

            return response()->json(["status" => 'success', "message" => "berhasil menghapus pegawai"], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["status" => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
