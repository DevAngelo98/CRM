@extends('layouts.app')
@section('content')

@if(session()->has('msg'))
<div class="row">
  <div class="col-12">
    <div class="alert alert-danger" role="alert">
      {{ session()->get('msg') }}
    </div>
  </div>
</div>
@endif

<form action="{{ route('order.post.create') }}" method="POST">
  @csrf
  @method("POST")
  @include('orders._form')
  <button type="submit" class="btn btn-primary">Save</button>
</form>
@endsection