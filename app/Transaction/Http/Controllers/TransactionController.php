<?php

declare(strict_types=1);

namespace App\Transaction\Http\Controllers;

use App\Base\Http\Controllers\Controller;
use App\Transaction\Exceptions\SellerTransactionException;
use App\Transaction\Services\CreateTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private CreateTransaction $createTransaction;

    public function __construct(CreateTransaction $createTransaction)
    {
        $this->createTransaction = $createTransaction;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'payer' => 'required|integer|exists:users,id',
            'payee' => 'required|integer|exists:users,id',
            'value' => 'required|numeric'
        ]);

        try {
            $this->createTransaction->create($request->all());
        } catch (SellerTransactionException $exception) {
            return response()->json(['message' => $exception->getMessage()], 403);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    
        return response()->json(['message' => 'Transaction registered with success.'], 201);
    }
}