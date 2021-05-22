<?php

declare(strict_types=1);

namespace App\Transaction\Http\Controllers;

use App\Base\Http\Controllers\Controller;
use App\Transaction\Events\TransactionNotificationEvent;
use App\Transaction\Exceptions\SellerTransactionException;
use App\Transaction\Exceptions\UnauthorizedTransactionException;
use App\Transaction\Services\CreateTransaction;
use App\Transaction\Services\TransactionAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    
    /**
     * @OA\Post(
     *     path="/transactions",
     *     tags={"Transactions"},
     *     description="Store transaction",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Transaction object that needs to be created",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="payer", type="integer"),
     *                 @OA\Property(property="payee", type="integer"),
     *                 @OA\Property(property="value", type="number")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized transaction"
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
            'payer' => 'required|integer|exists:users,id',
            'payee' => 'required|integer|exists:users,id',
            'value' => 'required|numeric'
        ]);

        try {
            Log::info("Registering new transaction");
            $transaction = $this->createTransaction->create($request->all());
            Log::info("Transaction {$transaction->id} registered");
            
            $this->transactionAuthorization->authorize($transaction);
            Log::info("Transaction authorized");
            
            event(new TransactionNotificationEvent($transaction));
            Log::info("Notifications sent");
        } catch (SellerTransactionException | UnauthorizedTransactionException $exception) {
            Log::error("Unauthorized transaction. Cause: {$exception->getMessage()}");
            return response()->json(['message' => $exception->getMessage()], 403);
        }
    
        return response()->json(['message' => 'Transaction registered with success.'], 201);
    }
}