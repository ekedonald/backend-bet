@extends('layouts.dashboard')

@section('title', 'Transaction Details')

@section('content')
<div class="col-lg-9 col-md-9 col-sm-6">
    <div class="card">
        <div class="card-header flex-wrap d-flex justify-content-between border-0">
            <div>
                <h4 class="card-title">Transaction</h4>
                <p class="m-0 subtitle">Transaction data</p>
            </div>
            <div>
                @if($transaction->isPending)
                    <form method="POST" action="{{ route('withdrawals.approve') }}">
                        @csrf
                        <input type="hidden" value="{{ $transaction->id }}" name="transaction_id" />
                        <button type="submit" class="btn btn-primary">Approve</button>
                    </form>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <tbody>
                    <tr>
                        <th>Transaction ID</th>
                        <td>{{ $transaction->id }}</td>
                    </tr>
                    <tr>
                        <th>User ID</th>
                        <td>{{ $transaction->user_id }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $transaction->user->fullName() }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ $transaction->type }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $transaction->status }}</td>
                    </tr>
                    <tr>
                        <th>Reference No</th>
                        <td>{{ $transaction->reference_no }}</td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td>{{ $transaction->amount }}</td>
                    </tr>
                    <tr>
                        <th>Sending Currency</th>
                        <td>{{ $transaction->sending_currency }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $transaction->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $transaction->updated_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
