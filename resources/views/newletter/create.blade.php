@extends('layouts.dashboard')

@section('title')
Add a newsletter
@endsection

@section('content')
<div class="col-lg-12 col-md-12 col-sm-12">
  <div class="card">
    <div class="card-header flex-wrap d-flex justify-content-between  border-0">
      <div>
        <h4 class="card-title">Add Newsletter</h4>
        <p class="m-0 subtitle">Create a new <code>newsletter</code></p>
      </div>
      <ul class="nav nav-tabs dzm-tabs" id="myTab-9" role="tablist">
        <li class="nav-item" role="presentation">
          <a href="{{ route('newsletter.index') }}" class="nav-link active " id="home-tab-9" role="tab">All Newsletter</a>
        </li>
      </ul>	
    </div>
    <div class="card-body col-md-12 py-10 px-10">
    <form method="POST" action="{{ route('newsletter.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="mt-5">
        <label for="name" class="form-label">Name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" placeholder="Jane Doe" required>
        @if($errors->has('name'))
          <div class="invalid-feedback">
            {{ $errors->first('name') }}
          </div>
        @endif
      </div>

      <div class="mt-5">
        <label for="email" class="form-label">Email</label>
        <input type="text" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email address">
        @if($errors->has('email'))
          <div class="invalid-feedback">
            {{ $errors->first('email') }}
          </div>
        @endif
      </div>

      <button type="submit" class="btn btn-primary mt-5">
        Save
      </button>
    </form>
    </div>
  </div>
</div>
@endsection