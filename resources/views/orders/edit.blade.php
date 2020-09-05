@extends('layouts.app')
@section('content')

<form action="{{ route('order.update', $order) }}" method="POST">
  @csrf
  @method("POST")
  @include('orders._form')
  <button type="submit" class="btn btn-warning">Update</button>
</form>
@stop