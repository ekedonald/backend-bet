@extends('layouts.dashboard')

@section('title')
Tokens
@endsection

@section('content')
<div class="col-lg-9 col-md-9 col-sm-6">
  <div class="card">
    <div class="card-header flex-wrap d-flex justify-content-between  border-0">
      <div>
        <h4 class="card-title">Token</h4>
        <p class="m-0 subtitle">Token data</p>
      </div>
      <ul class="nav nav-tabs dzm-tabs" id="myTab-9" role="tablist">
        <li class="nav-item" role="presentation">
          <a href="{{ route('tokens.create') }}" class="nav-link active " id="home-tab-9" role="tab">Add Token</a>
        </li>
      </ul>	
    </div>
    <div class="card-body p-0">
      <div class="table-responsive active-projects">
        <table id="projects-tbl4" class="table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Symbol</th>
              <th>Created At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($tokens as $token)
              <tr>
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $token->name }}</h6>
                    </div>	
                  </div>
                </td>	
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $token->symbol }}</h6>
                    </div>	
                  </div>
                </td>	
                <td>{{ $token->created_at }}</td>
                <td>
                  <div class="d-flex">
                    <a href="{{ route('tokens.edit', ['id' => $token->id]) }}" class="btn btn-primary shadow btn-xs sharp me-1"><i class="fa fa-pencil"></i></a>
                    <form method="POST" action="{{ route('tokens.delete', ['id' => $token->id]) }}">
                      @csrf
                      @method('DELETE')
                      <a 
                        href="{{ route('tokens.delete', ['id' => $token->id]) }}" 
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
                  <h4 class="mt-5 text-center">No token Avaliable, try creating a new token</h4>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-2">
        {{ $tokens->links() }}
      </div>
    </div>
  </div>
</div>
@endsection