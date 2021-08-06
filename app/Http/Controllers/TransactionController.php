<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\TransactionService;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use Illuminate\Support\Facades\Redis;

class TransactionController extends Controller
{

    //
    private $transactionService ;
    public function __construct(TransactionService $transactionService) {
        $this->transactionService = $transactionService;
    }

    public function store(TransactionRequest $request)
    {
        $transaction = $request->all();
        return  new TransactionResource($this->transactionService->process((object)collect($transaction)->all()));


    }

}
