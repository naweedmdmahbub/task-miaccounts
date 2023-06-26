<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $limit = Arr::get($searchParams, 'limit', 10);
        $keyword = Arr::get($searchParams, 'keyword', '');
        $transactionsQuery = Transaction::with(['accountHead' => function ($query) {
                                $query->select('id', 'name');
                            }])
                            ->select('date', 'id', 'account_head_id', 'debit', 'credit',
                                    DB::raw('(debit-credit) as amount')
                            )
                            ->when(!empty($keyword), function ($query) use ($keyword) {
                                return $query->whereHas('accountHead',function ( $subquery ) use ($keyword){
                                                $subquery->where('name', 'LIKE', '%' . $keyword . '%');
                                            });
                            });

        return response()->json($transactionsQuery->paginate($limit));
    }

    public function store(TransactionRequest $request)
    {
        try {
            $transactionData = $request->only('account_head_id', 'date', 'debit', 'credit');
            $transaction = Transaction::create($transactionData);
            return $transaction;
        } catch (Exception $ex) {
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }

    

    public function edit($id)
    {
        $transaction = Transaction::with('accountHead')->find($id);
        return response()->json($transaction);
    }


    public function update(TransactionRequest $request, $id)
    {
        try {
            $transactionData = $request->only('account_head_id', 'date', 'debit', 'credit');
            $transaction = Transaction::find($id);
            $transaction->update($transactionData);
            return $transaction;
        } catch (Exception $ex) {
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }

    public function delete($id)
    {
        try {
            $transaction = Transaction::find($id);;
            $transaction->delete();
            return 'Delete Successful';
        } catch (Exception $ex) {
            return 'Delete Failed';
        }
    }

    
    public function getAllTransactions()
    {
        $transactions = Transaction::with(['accountHead' => function ($query) {
                                $query->select('id', 'name');
                            }])->get();
        return response()->json($transactions);
    }
}
