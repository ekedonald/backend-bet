@extends('layouts.dashboard')

@section('title')
Create Token
@endsection

@section('content')
<div class="col-xl-6 col-lg-6">
  <div class="card">
    <div class="card-header flex-wrap d-flex justify-content-between border-0">
      <div>
        <h4 class="card-title">Create a Token</h4>
        <p class="m-0 subtitle">Add a new Token</p>
      </div>
      <ul class="nav nav-tabs dzm-tabs" id="myTab-9" role="tablist">
        <li class="nav-item" role="presentation">
          <a href="{{ route('tickers.index') }}" class="nav-link active" id="home-tab-9" role="tab">All Tokens</a>
        </li>
      </ul>  
    </div>
    <div class="card-body">
      <div class="basic-form">
        <form action="{{ route('tokens.store') }}" method="POST" id="token-form">
          @csrf

          <div class="mb-3">
            <label class="mb-1 form-label">Browse token from binance</label>
            <input 
              type="text" 
              name="tokenSymbol" 
              id="token-name"
              class="form-control" 
              placeholder="Ticker"
              autocomplete="tokenSymbol" 
              autofocus  
              value="{{ old('tokenSymbol') }}"
            >
            <div id="token-list" class="dropdown-menu"></div>
            @if($errors->has('tokenSymbol'))
              <div class="invalid-feedback">
                {{ $errors->first('tokenSymbol') }}
              </div>
            @endif
          </div>

          <div class="mb-3">
            <label class="mb-1 form-label">Token Name</label>
            <input 
              type="text" 
              name="name" 
              class="form-control" 
              placeholder="Token name"
              autocomplete="name" 
              autofocus  
              value="{{ old('name') }}"
            />
            @if($errors->has('name'))
              <div class="invalid-feedback">
                {{ $errors->first('name') }}
              </div>
            @endif
          </div>

          <input type="hidden" name="symbol" id="token-symbol">
          <input type="hidden" name="tokenObject" id="token-object">

          <div class="">
            <button type="submit" class="btn btn-primary">Create</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  $(document).ready(function() {
    let debounceTimer;
    let previousRequest = null;

    $('#token-name').on('input', function() {
      clearTimeout(debounceTimer);
      const query = $(this).val();

      if (query.length > 2) {
        debounceTimer = setTimeout(() => {
          if (previousRequest) {
            previousRequest.abort();
          }

          previousRequest = $.ajax({
            url: 'https://api.binance.com/api/v3/ticker/price',
            type: 'GET',
            success: function(data) {
              const filteredData = data.filter(token => token.symbol.toLowerCase().includes(query.toLowerCase())).slice(0, 10);
              const dropdown = $('#token-list');
              dropdown.empty().show();
              filteredData.forEach(function(token) {
                dropdown.append('<a class="dropdown-item" href="#" data-symbol="' + token.symbol + '" data-object=\'' + JSON.stringify(token) + '\'>' + token.symbol + '</a>');
              });
            },
            complete: function() {
              previousRequest = null;
            }
          });
        }, 300); // Adjust the delay as needed
      } else {
        if (previousRequest) {
          previousRequest.abort();
        }
        $('#token-list').empty().hide();
      }
    });

    $(document).on('click', '#token-list .dropdown-item', function(e) {
      e.preventDefault();
      const symbol = $(this).data('symbol');

      if (previousRequest) {
        previousRequest.abort();
      }

      previousRequest = $.ajax({
        url: 'https://api.binance.com/api/v3/ticker/price?symbol=' + symbol,
        type: 'GET',
        success: function(token) {
          const name = symbol;  // Assume the name is the symbol for simplicity
          $('#token-name').val(name);
          $('#token-symbol').val(symbol);
          $('#token-object').val(JSON.stringify(token));
          $('#token-list').hide();
        },
        complete: function() {
          previousRequest = null;
        }
      });
    });
  });
</script>
@endsection
