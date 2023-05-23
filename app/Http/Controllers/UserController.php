<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    /**
     * @OA\POST(
     *     path="/api/auth/users",
     *     tags={"Users"},
     *     summary="Register User",
     *     description="Register New User",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Kelvin Henrique"),
     *              @OA\Property(property="email", type="string", example="kelvin@teste.com"),
     *              @OA\Property(property="password", type="string", example="123456789")
     *          ),
     *      ),
     *      @OA\Response(response=200, description="Register New User Data" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
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

    /**
     * @OA\GET(
     *     path="/api/auth/users",
     *     tags={"Users"},
     *     summary="Users List",
     *     description="Users List as Array",
     *     operationId="index",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200,description="Users List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index()
    {
        try {
            $users = (new User)->users();

            return UserResource::collection($users);
        } catch (\Exception $e) {
            return response()->json(['Errors' => [$e->getMessage()]], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/auth/users/{id}",
     *     tags={"Users"},
     *     summary="Get specific user (UUID)",
     *     description="Get specific user (UUID)",
     *     operationId="show",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200, description="Get specific user (UUID)"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['Errors' => [$e->getMessage()]], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * @OA\PUT(
     *     path="/api/auth/users/{id}",
     *     tags={"Users"},
     *     summary="Update User",
     *     description="Update User",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Kelvin Henrique"),
     *              @OA\Property(property="email", type="string", example="kelvin@teste.com"),
     *              @OA\Property(property="password", type="string", example="123456789")
     *          ),
     *      ),
     *     operationId="update",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200, description="Update Product"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
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

    /**
     * @OA\DELETE(
     *     path="/api/auth/users/{id}",
     *     tags={"Users"},
     *     summary="Delete User",
     *     description="Delete User",
     *     operationId="destroy",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200, description="Delete User"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
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