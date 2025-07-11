<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Carga las relaciones 'stories' y 'achievements' con sus pivotes
        $user->load(['stories' => function ($query) {
            $query->withPivot('learned_at');
        }, 'achievements' => function ($query) {
            $query->withPivot('awarded_at');
        }]);

        return new UserResource($user);
    }
}
