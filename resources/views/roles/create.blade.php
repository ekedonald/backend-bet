@extends('layouts.dashboard')

@section('title')
Add Role
@endsection

@section('content')
<div class="col-xl-6 col-lg-6">
  <div class="card">
    <div class="card-header flex-wrap d-flex justify-content-between  border-0">
      <div>
        <h4 class="card-title">Create a Role</h4>
        <p class="m-0 subtitle">Add a new role</p>
      </div>
      <ul class="nav nav-tabs dzm-tabs" id="myTab-9" role="tablist">
        <li class="nav-item" role="presentation">
          <a href="{{ route('role.index') }}" class="nav-link active " id="home-tab-9" role="tab">All Role</a>
        </li>
      </ul>	
    </div>
    <div class="card-body">
      <div class="basic-form">
        <form action="{{ route('role.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="mb-1 form-label">Name</label>
            <input 
              type="text" 
              name="name" 
              class="form-control" 
              placeholder="Role Name"
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
            
          <div class="">
            <button type="submit" class="btn btn-primary">Create</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection