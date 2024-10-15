@extends('layouts.dashboard')

@section('title')
Create Ticker
@endsection

@section('content')
<div class="col-xl-6 col-lg-6">
  <div class="card">
    <div class="card-header flex-wrap d-flex justify-content-between  border-0">
      <div>
        <h4 class="card-title">Create a Ticker</h4>
        <p class="m-0 subtitle">Add a new Ticker</p>
      </div>
      <ul class="nav nav-tabs dzm-tabs" id="myTab-9" role="tablist">
        <li class="nav-item" role="presentation">
          <a href="{{ route('tickers.index') }}" class="nav-link active " id="home-tab-9" role="tab">All Tickers</a>
        </li>
      </ul>	
    </div>
    <div class="card-body">
      <div class="basic-form">
        <form action="{{ route('tickers.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="mb-1 form-label">Base Token</label>
            <select 
              name="base_token_id" 
              class="form-control" 
              class="form-control{{ $errors->has('base_token_id') ? ' is-invalid' : '' }}"
            >
              @foreach ($tokens as $token)
                <option value="{{ $token->id }}">{{ $token->name }}</option>
              @endforeach
            </select>
            @if($errors->has('base_token_id'))
              <div class="invalid-feedback">
                {{ $errors->first('base_token_id') }}
              </div>
            @endif
          </div>

          <div class="mb-3">
            <label class="mb-1 form-label">Target Token</label>
            <select 
              name="target_token_id" 
              class="form-control" 
              class="form-control{{ $errors->has('base_token_id') ? ' is-invalid' : '' }}"
            >
              @foreach ($tokens as $token)
                <option value="{{ $token->id }}">{{ $token->name }}</option>
              @endforeach
            </select>
            @if($errors->has('target_token_id'))
              <div class="invalid-feedback">
                {{ $errors->first('target_token_id') }}
              </div>
            @endif
          </div>
            
          <div class="">
            <button type="submit" class="btn btn-primary">Create</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection