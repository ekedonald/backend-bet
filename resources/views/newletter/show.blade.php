@extends('layouts.dashboard')

@section('title')
  Show Event
@endsection

@section('content')
<div class="col-lg-12 col-md-12 col-sm-12">
  <div class="card">
    <div class="card-header flex-wrap d-flex justify-content-between border-0">
      <div>
        <h4 class="card-title">View Event</h4>
        <p class="m-0 subtitle">Event details <code>{{ $event->name }}</code></p>
      </div>
      <ul class="nav nav-tabs dzm-tabs" id="myTab-9" role="tablist">
        <li class="nav-item" role="presentation">
          <a href="{{ route('event.edit', ['id' => $event->id]) }}" class="nav-link" id="home-tab-9" role="tab">Edit Event</a>
        </li>
        <li class="nav-item ml-2" role="presentation">
          <a href="{{ route('event.index') }}" class="nav-link active " id="home-tab-9" role="tab">Back</a>
        </li>
      </ul>	
    </div>
    <div class="m-5">
      <div class="row">
        <div class="col-md-6">
          <div class="productImageWrapper">
            <img src="{{ $event->image }}" alt="{{ $event->name }}" />
          </div>
        </div>
        <div class="col-md-6">
          <h2 class="font-bold">{{ $event->name }}</h2>
          <h6 class="font-bold">{{ $event->location }}, {{ $event->event_date }}</h6>
          <h5>{!! $event->description !!}</h5>
          <a href="{{ route('event.edit', ['id' => $event->id]) }}" class="btn btn-primary">Edit Event</a>
        </div>
      </div>
    </div> 
  </div>
</div>
@endsection