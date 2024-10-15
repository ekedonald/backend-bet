@extends('layouts.dashboard')

@section('title')
Edit Newsletter
@endsection

@section('content')
<div class="col-lg-12 col-md-12 col-sm-12">
  <div class="card">
    <div class="card-header flex-wrap d-flex justify-content-between  border-0">
      <div>
        <h4 class="card-title">Edit Newsletter</h4>
        <p class="m-0 subtitle">Edit a <code>newsletter</code></p>
      </div>
      <ul class="nav nav-tabs dzm-tabs" id="myTab-9" role="tablist">
        <li class="nav-item" role="presentation">
          <a href="{{ route('newsletter.index') }}" class="nav-link active " id="home-tab-9" role="tab">Back</a>
        </li>
      </ul>	
    </div>
    <div class="card-body col-md-12 py-10 px-10">
    <form method="POST" action="{{ route('newsletter.update', ['id' => $newsletter->id]) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="mt-5">
        <label for="name" class="form-label">Name</label>
        <input type="text" value="{{ $newsletter->name }}" id="name" name="name" value="{{ old('name') }}" class="form-control" placeholder="Newsletter name." required>
        @if($errors->has('name'))
          <div class="invalid-feedback">
            {{ $errors->first('name') }}
          </div>
        @endif
      </div>

      <div class="mt-5">
        <label for="email" class="form-label">Email</label>
        <input type="text" id="email" name="email" value="{{ $newsletter->email }}" class="form-control" placeholder="Lagos, Nigeria">
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

@section('script')
<script src="https://cdn.tiny.cloud/1/9u7si4bhn2d98cqkrecf08v3zrcu0fl8eiuaqddv8pznskty/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: '#editor',
    plugins: 'code table lists',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
  });
</script>
@endsection

@section('style')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css" rel="stylesheet" />
@endsection