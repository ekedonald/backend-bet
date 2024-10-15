@extends('layouts.dashboard')

@section('title')
Transactions
@endsection

@section('content')
<div class="col-lg-9 col-md-9 col-sm-6">
  <div class="card">
    <div class="card-header flex-wrap d-flex justify-content-between  border-0">
      <div>
        <h4 class="card-title">Transaction</h4>
        <p class="m-0 subtitle">Transaction data</p>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive active-projects">
        <table id="projects-tbl4" class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Type</th>
              <th>Amount</th>
              <th>Date Created</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($transactions as $transaction)
              <tr>
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $transaction->id }}</h6>
                    </div>	
                  </div>
                </td>	
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $transaction->type }}</h6>
                    </div>	
                  </div>
                </td>	
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $transaction->amount }}</h6>
                    </div>	
                  </div>
                </td>	
                <td>{{ $transaction->created_at }}</td>
                <td>
                  <div class="d-flex">
                    <a href="{{ route('transactions.show', ['id' => $transaction->id]) }}" class="btn btn-warning shadow btn-xs sharp me-1"><i class="fa fa-eye"></i></a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td>
                  <h4 class="mt-5 text-center">No transaction Avaliable, try creating a new transaction</h4>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-2">
        {{ $transactions->links() }}
      </div>
    </div>
  </div>
</div>
@endsection