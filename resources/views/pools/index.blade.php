@extends('layouts.dashboard')

@section('title')
Pools
@endsection

@section('content')
<div class="col-lg-9 col-md-9 col-sm-6">
  <div class="card">
    <div class="card-header flex-wrap d-flex justify-content-between  border-0">
      <div>
        <h4 class="card-title">Pool</h4>
        <p class="m-0 subtitle">Pool data</p>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive active-projects">
        <table id="projects-tbl4" class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Ticker</th>
              <th>Date Created</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($pools as $pool)
              <tr>	
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $pool->id }}</h6>
                    </div>	
                  </div>
                </td>	
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $pool->ticker->name }}</h6>
                    </div>	
                  </div>
                </td>	
                <td>{{ $pool->created_at }}</td>
                <td>
                  <div class="d-flex">
                    <a href="{{ route('pools.show', ['id' => $pool->id]) }}" class="btn btn-warning shadow btn-xs sharp me-1"><i class="fa fa-eye"></i></a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td>
                  <h4 class="mt-5 text-center">No pool Avaliable, try creating a new pool</h4>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-2">
        {{ $pools->links() }}
      </div>
    </div>
  </div>
</div>
@endsection