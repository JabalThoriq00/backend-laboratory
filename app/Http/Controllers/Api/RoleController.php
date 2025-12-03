<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index(): JsonResponse
    {
        $roles = Role::all();

        return response()->json([
            'success' => true,
            'data' => RoleResource::collection($roles),
        ]);
    }
}
