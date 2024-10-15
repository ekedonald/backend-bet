@extends('layouts.dashboard')

@section('title')
Dashboard
@endsection

@section('content')
<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <div class="ds-head">
          <h6>Total Transaction Amount</h6>
          <h3 class="d-flex align-items-center justify-content-between">{{ number_format($totalTransactionAmount, 2, '.', ',') }}</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <div class="ds-head">
          <h6>Total Transaction</h6>
          <h3 class="d-flex align-items-center justify-content-between">{{ $totalTransaction }}</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <div class="ds-head">
          <h6>Pending Withdrawals</h6>
          <h3 class="d-flex align-items-center justify-content-between">{{ $totalPendingTransaction }}</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <div class="ds-head">
          <h6>Total Tickers</h6>
          <h3 class="d-flex align-items-center justify-content-between">{{ $totalTicker }}</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <div class="ds-head">
          <h6>Total Tokens</h6>
          <h3 class="d-flex align-items-center justify-content-between">{{ $totalToken }}</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <div class="ds-head">
          <h6>Total Pools</h6>
          <h3 class="d-flex align-items-center justify-content-between">{{ $totalPool }}</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <div class="ds-head">
          <h6>Total Users</h6>
          <h3 class="d-flex align-items-center justify-content-between">{{ $totalUser }}</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <div class="ds-head">
          <h6>Total Permissions</h6>
          <h3 class="d-flex align-items-center justify-content-between">{{ $totalPermission }}</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <div class="ds-head">
          <h6>Total Roles</h6>
          <h3 class="d-flex align-items-center justify-content-between">{{ $totalRole }}</h3>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection