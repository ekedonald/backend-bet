@extends('layouts.dashboard')

@section('title')
Tickers
@endsection

@section('content')
<div class="col-lg-9 col-md-9 col-sm-6">
  <div class="card">
    <div class="card-header flex-wrap d-flex justify-content-between  border-0">
      <div>
        <h4 class="card-title">Ticker</h4>
        <p class="m-0 subtitle">Ticker data</p>
      </div>
      <ul class="nav nav-tabs dzm-tabs" id="myTab-9" role="tablist">
        <li class="nav-item" role="presentation">
          <a href="{{ route('tickers.create') }}" class="nav-link active " id="home-tab-9" role="tab">Add Ticker</a>
        </li>
      </ul>	
    </div>
    <div class="card-body p-0">
      <div class="table-responsive active-projects">
        <table id="projects-tbl4" class="table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Base Token</th>
              <th>Target Token</th>
              <th>Date Created</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($tickers as $ticker)
              <tr>
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $ticker->name }}</h6>
                    </div>	
                  </div>
                </td>	
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $ticker->base_token->name }}</h6>
                    </div>	
                  </div>
                </td>	
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $ticker->target_token->name }}</h6>
                    </div>	
                  </div>
                </td>	
                <td>{{ $ticker->created_at }}</td>
                <td>
                  <div class="d-flex">
                    <a href="{{ route('tickers.edit', ['id' => $ticker->id]) }}" class="btn btn-primary shadow btn-xs sharp me-1"><i class="fa fa-pencil"></i></a>
                    <form method="POST" action="{{ route('tickers.delete', ['id' => $ticker->id]) }}">
                      @csrf
                      @method('DELETE')
                      <a 
                        href="{{ route('tickers.delete', ['id' => $ticker->id]) }}" 
                        class="btn btn-danger shadow btn-xs sharp"
                        onclick="event.preventDefault();
                                  this.closest('form').submit();"
                      >
                        <i class="fa fa-trash"></i>
                      </a>
                  </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td>
                  <h4 class="mt-5 text-center">No ticker Avaliable, try creating a new ticker</h4>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-2">
        {{ $tickers->links() }}
      </div>
    </div>
  </div>
</div>
@endsection