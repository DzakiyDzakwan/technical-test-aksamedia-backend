<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $profile = User::where('uuid', $request->id);

            $profile->name = $request->name;

            if ($request->phone) {
                $profile->phone = $request->phone;
            }

            $profile->save();

            return response()->json([
                "status" => 'success',
                "message" => "berhasil mengubah profil",
                "data" => new UserResource($profile)
            ], 200);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["status" => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
