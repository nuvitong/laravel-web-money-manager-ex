<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\Payee;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use App\Services\TransactionService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * @var TransactionService
     */
    private $transactionService;

    /**
     * TransactionController constructor.
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {

        $this->transactionService = $transactionService;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('transactions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TransactionRequest|Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request)
    {
        $files = $request->file('attachments');

        $data = collect($request->all());
        $this->transactionService->createTransaction(Auth::user(), $data, $files);

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TransactionRequest|Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionRequest $request, $id)
    {
        $files = $request->file('attachments');

        $data = collect($request->all());
        $this->transactionService->updateTransaction(Auth::user(), $id, $data, $files);

        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_unless(Auth::user()->transactions()->whereId($id)->exists(), 403);

        Transaction::destroy($id);
    }
}
