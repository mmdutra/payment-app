<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\User\DocumentAlreadyExistsException;
use App\Services\User\CreateUser;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private CreateUser $createUser;

    public function __construct(CreateUser $createUser)
    {
        $this->createUser = $createUser;
    }

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
