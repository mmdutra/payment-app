<?php

declare(strict_types=1);

namespace App\User\Http\Controllers;

use App\Base\Http\Controllers\Controller;
use App\User\Exceptions\DocumentAlreadyExistsException;
use App\User\Services\CreateUser;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private CreateUser $createUser;

    public function __construct(CreateUser $createUser)
    {
        $this->createUser = $createUser;
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     tags={"Users"},
     *     description="Store user",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User object that needs to be created",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string", format="email"),
     *                 @OA\Property(property="cpf", description="CPF/CNPJ", type="string"),
     *                 @OA\Property(property="password", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Document already exists exception"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid data"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users',
            'cpf' => 'required|string',
            'password' => 'required|string'
        ]);

        try {
            $user = $this->createUser->execute($request->all());
        }
        catch (DocumentAlreadyExistsException $exception) {
            return response()->json(['cpf' => $exception->getMessage()], 403);
        } catch (\InvalidArgumentException $exception) {
            return response()->json(['cpf' => $exception->getMessage()], 422);
        }

        return response()->json(['id' => $user->id], 201);
    }
}
