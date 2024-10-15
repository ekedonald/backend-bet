@extends('layouts.dashboard')

@section('title')
Users
@endsection

@section('content')
<div class="col-lg-9 col-md-9 col-sm-6">
  <div class="card">
    <div class="card-header flex-wrap d-flex justify-content-between  border-0">
      <div>
        <h4 class="card-title">User</h4>
        <p class="m-0 subtitle">User data</p>
      </div>
      <ul class="nav nav-tabs dzm-tabs" id="myTab-9" role="tablist">
        <li class="nav-item" role="presentation">
          <a href="{{ route('user.add') }}" class="nav-link active " id="home-tab-9" role="tab">Add User</a>
        </li>
      </ul>	
    </div>
    <div class="card-body p-0">
      <div class="table-responsive active-projects">
        <table id="projects-tbl4" class="table">
          <thead>
            <tr>
              <th>User Name</th>
              <th>User Email</th>
              <th>User Role</th>
              <th>Date Created</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
              <tr>
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $user->fullName() }}</h6>
                    </div>	
                  </div>
                </td>	
                <td>
                  <div class="products">
                    <div>
                      <h6>{{ $user->email }}</h6>
                    </div>	
                  </div>
                </td>	
                <td>
                  <div class="products">
                    <div>
                      @forelse ($user->roles as $role)
                        <span class="badge badge-success light mr-3">{{ $role->name }}</span>
                      @empty
                        <h6>No role assign to user</h6>
                      @endforelse
                    </div>	
                  </div>
                </td>	
                <td>{{ $user->created_at }}</td>
                <td>
                  <div class="d-flex">
                    <a href="{{ route('user.edit', ['id' => $user->id]) }}" class="btn btn-primary shadow btn-xs sharp me-1"><i class="fa fa-pencil"></i></a>
                    <form method="POST" action="{{ route('user.delete', ['id' => $user->id]) }}">
                      @csrf
                      @method('DELETE')
                      <a 
                        href="{{ route('user.delete', ['id' => $user->id]) }}" 
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
                  <h4 class="mt-5 text-center">No User Avaliable, try creating a new user</h4>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="px-2">
        {{ $users->links() }}
      </div>
    </div>
  </div>
</div>
@endsection