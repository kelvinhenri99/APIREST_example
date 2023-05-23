<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        try {
            $user = User::create(
                array_merge(
                    [
                        "name" => $request->name,
                        "email" => $request->email,
                        "password" => bcrypt($request->password)
                    ]
                )
            );
            return response()->json(['Data' => new UserResource($user)], JsonResponse::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json(['Errors' => [$e->getMessage()]], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function index()
    {
        try {
            $users = (new User)->users();

            return UserResource::collection($users);
        } catch (\Exception $e) {
            return response()->json(['Errors' => [$e->getMessage()]], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['Errors' => [$e->getMessage()]], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function update(UserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $data = $request->all();
            $data['password'] = bcrypt($request->password);
            $user->update($data);

            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['Errors' => [$e->getMessage()]], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->delete();

            return response()->json(['Message:' => 'User deleted']);
        } catch (\Exception $e) {
            return response()->json(['Errors' => [$e->getMessage()]], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}