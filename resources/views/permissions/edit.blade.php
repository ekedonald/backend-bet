@extends('layouts.dashboard')

@section('title')
Edit Permissions
@endsection

@section('content')
<div class="col-xl-6 col-lg-6">
  <div class="card">
    <div class="card-header flex-wrap d-flex justify-content-between  border-0">
      <div>
        <h4 class="card-title">Edit a Permission</h4>
        <p class="m-0 subtitle">You want to make changes to <code>{{ $permission->name }}</code></p>
      </div>
      <ul class="nav nav-tabs dzm-tabs" id="myTab-9" role="tablist">
        <li class="nav-item" role="presentation">
          <a href="{{ route('permission.index') }}" class="nav-link active " id="home-tab-9" role="tab">Back</a>
        </li>
      </ul>	
    </div>
    <div class="card-body">
      <div class="basic-form">
        <form action="{{ route('permission.update', ['id'=>$permission->id]) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label class="mb-1 form-label">Name</label>
            <input 
              type="text" 
              name="name" 
              class="form-control" 
              placeholder="Permission Name"
              class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
              autocomplete="name" 
              autofocus  
              value="{{ $permission->name }}"
            >
            @if($errors->has('name'))
              <div class="invalid-feedback">
                {{ $errors->first('name') }}
              </div>
            @endif
          </div>
            
          <div class="">
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection