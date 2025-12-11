<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        // Поиск по имени или email
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Пагинация
        $perPage = $request->get('per_page', 10);
        if ($perPage == 'all') {
            $users = $query->orderBy('name', 'asc')->get();
            return response()->json(['data' => $users]);
        } else {
            $users = $query->orderBy('name', 'asc')->paginate($perPage);
            return response()->json($users);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'role' => 'required|in:admin,moderator,user',
        ]);

        $user->update($request->only(['role']));

        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Предотвратить удаление самого себя
        if ($user->id == $request->user()->id) {
            return response()->json(['message' => 'Cannot delete yourself'], 400);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
