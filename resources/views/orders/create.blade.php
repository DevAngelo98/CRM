@extends('layouts.app')
@section('content')

<form action="{{ route('order.post.create') }}" method="POST">
  @csrf
  @method("POST")
  @include('orders._form')
  <button type="submit" class="btn btn-primary">Save</button>
</form>
@endsection