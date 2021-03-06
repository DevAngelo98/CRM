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
  <form action="{{ route('customers.update', $customer) }}" method="POST">
    @csrf
    @method('PUT')
    @include('customers._form')
    <button type="submit" class="btn btn-warning">Update</button>
  </form>
@stop
