@extends('layouts.dashboard')

@section('title')
Newsletter
@endsection

@section('content')
<div class="col-lg-12 col-md-12 col-sm-12">
  <div class="card">
    <div class="card-header flex-wrap d-flex justify-content-between  border-0">
      <div>
        <h4 class="card-title">Newsletter</h4>
        <p class="m-0 subtitle">Newsletter data</p>
      </div>
      <ul class="nav nav-tabs dzm-tabs" id="myTab-9" role="tablist">
        <li class="nav-item" role="presentation">
          <a href="{{ route('newsletter.add') }}" class="nav-link active " id="home-tab-9" role="tab">Add Newsletter</a>
        </li>
      </ul>	
    </div>
    <div class="card-body p-0">
      <div class="table-responsive active-projects">
        <table id="projects-tbl4" class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Date Created</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($newsletters as $newsletter)
              <tr>
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $newsletter->id }}</h6>
                    </div>	
                  </div>
                </td>	
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $newsletter->name }}</h6>
                    </div>	
                  </div>
                </td>	
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $newsletter->email }}</h6>
                    </div>	
                  </div>
                </td>		
                <td>{{ $newsletter->created_at }}</td>
                <td>
                  <div class="d-flex">
                    <a href="{{ route('newsletter.edit', ['id' => $newsletter->id]) }}" class="btn btn-primary shadow btn-xs sharp me-1"><i class="fa fa-pencil"></i></a>
                    <form method="POST" action="{{ route('newsletter.delete', ['id' => $newsletter->id]) }}">
                      @csrf
                      @method('DELETE')
                      <a 
                        href="{{ route('newsletter.delete', ['id' => $newsletter->id]) }}" 
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
                  <h4 class="mt-5 text-center">No newsletter avaliable, try creating a new newsletter</h4>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    </div>
  </div>
</div>
@endsection

@section('style')
<!-- <script src="https://cdn.tailwindcss.com"></script> -->
@endsection