<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::with('role');

        // Filter by role if provided
        if ($request->has('role')) {
            $role = Role::where('name', $request->role)->first();
            if ($role) {
                $query->where('role_id', $role->id);
            }
        }

        // Search by name or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $users = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    /**
     * Store a newly created user
     */
    public function store(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        $user->load('role');

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => new UserResource($user),
        ], 201);
    }

    /**
     * Display the specified user
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $user = User::with('role')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Check if user can view this profile
        $authUser = $request->user();
        if (!$authUser->isAdmin() && !$authUser->isDosen() && $authUser->id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view this user',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Update the specified user
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Check if user can update this profile
        $authUser = $request->user();
        if (!$authUser->isAdmin() && $authUser->id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this user',
            ], 403);
        }

        // Prevent non-admin from changing role
        if (!$authUser->isAdmin() && $request->has('role_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Only admin can change user role',
            ], 403);
        }

        $validated = $request->validated();
        
        // Hash password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        $user->load('role');

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Remove the specified user
     */
    public function destroy(string $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
}
