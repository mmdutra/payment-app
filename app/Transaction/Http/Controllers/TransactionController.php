<?php

declare(strict_types=1);

namespace App\Transaction\Http\Controllers;

use App\Base\Http\Controllers\Controller;
use App\Transaction\Exceptions\SellerTransactionException;
use App\Transaction\Exceptions\UnauthorizedTransactionException;
use App\Transaction\Services\CreateTransaction;
use App\Transaction\Services\TransactionAuthorization;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private CreateTransaction $createTransaction;
    private TransactionAuthorization $transactionAuthorization;

    public function __construct(
        CreateTransaction $createTransaction,
        TransactionAuthorization $transactionAuthorization
    )
    {
        $this->createTransaction = $createTransaction;
        $this->transactionAuthorization = $transactionAuthorization;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'payer' => 'required|integer|exists:users,id',
            'payee' => 'required|integer|exists:users,id',
            'value' => 'required|numeric'
        ]);

        try {
            $transaction = $this->createTransaction->create($request->all());
            
            $this->transactionAuthorization->authorize($transaction);
        } catch (SellerTransactionException | UnauthorizedTransactionException $exception) {
            return response()->json(['message' => $exception->getMessage()], 403);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    
        return response()->json(['message' => 'Transaction registered with success.'], 201);
    }
}