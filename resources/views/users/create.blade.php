@extends('layouts.dashboard')

@section('title')
Create User
@endsection

@section('content')
<div class="col-xl-6 col-lg-6">
  <div class="card">
    <div class="card-header flex-wrap d-flex justify-content-between  border-0">
      <div>
        <h4 class="card-title">Create a User</h4>
        <p class="m-0 subtitle">Add a new user</p>
      </div>
      <ul class="nav nav-tabs dzm-tabs" id="myTab-9" role="tablist">
        <li class="nav-item" role="presentation">
          <a href="{{ route('user.index') }}" class="nav-link active " id="home-tab-9" role="tab">All Users</a>
        </li>
      </ul>	
    </div>
    <div class="card-body">
      <div class="basic-form">
        <form action="{{ route('user.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="mb-1 form-label">Name</label>
            <input 
              type="text" 
              name="name" 
              class="form-control" 
              placeholder="User Name"
              class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
              autocomplete="name" 
              autofocus  
              value="{{ old('name', null) }}"
            >
            @if($errors->has('name'))
              <div class="invalid-feedback">
                {{ $errors->first('name') }}
              </div>
            @endif
          </div>

          <div class="mb-3">
            <label class="mb-1 form-label">Email</label>
            <input 
              type="text" 
              name="email" 
              class="form-control" 
              placeholder="email"
              class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
              autocomplete="email" 
              autofocus  
              value="{{ old('email', null) }}"
            >
            @if($errors->has('email'))
              <div class="invalid-feedback">
                {{ $errors->first('email') }}
              </div>
            @endif
          </div>

          <div class="mb-3">
            <label class="mb-1 form-label">Assign to Role</label>
            <select 
              name="role_id" 
              class="form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}"
            >
              @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
              @endforeach
            </select>
            @if($errors->has('role_id'))
              <div class="invalid-feedback">
                {{ $errors->first('role_id') }}
              </div>
            @endif
          </div>
        
          <div class="mb-3 position-relative">
            <label class="form-label" for="dz-password">Password</label>
            <input 
              type="password" 
              name="password" 
              id="dz-password" 
              class="form-control" 
              value="123456"
              class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
            >
            <span class="show-pass eye">
              <i class="fa fa-eye-slash"></i>
              <i class="fa fa-eye"></i>
            </span>
            @if($errors->has('password'))
              <div class="invalid-feedback">
                {{ $errors->first('password') }}
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