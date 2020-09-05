@extends('layouts.app')

@section('content')

@if(count($orders))
<div class="row">
  <div class="offset-md-10 col-md-2">
    <a href="{{ route('order.create') }}" class="btn btn-primary btn-block">+ New Order</a>
  </div>
</div>
<br>
<div class="row">
  <div class="col-12">
    <h1 class="text-center text-success">Active orders</h1>
  </div>
  <div class="col-md-12">
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Contract with
          </th>
          <th scope="col">Tags</th>
          <th scope="col" colspan="2" class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
        <tr>
          <th scope="row">{{ $order->id }}</th>
          <td>{{ $order->title }}</td>
          <td>{{ $order->description }}</td>
          <td>
            {{-- {{ $order->contractName }} --}}

            @if($order->contractName != '')
            {{ $order->contractName }}
            @elseif($order->contractNameDeleted != '')
            {{ $order->contractNameDeleted }}
            <span>
              <a class="text-danger" href="">(Restore customer)</a>
            </span>
            @endif
          </td>
          <td>{{ $order->tagsName }}</td>


          <td><a href="{{ route('order.edit', $order) }}">[Edit]</a></td>
          <td><a href="#"
              onclick="event.preventDefault(); document.getElementById('delete-order-{{ $order->id }}-form').click();">[Delete]</a>
          </td>
          <td style="display: none;">
            <form action="{{ route('order.delete', $order) }}" method="POST">
              @method('DELETE')
              @csrf
              <button type="submit" id="delete-order-{{ $order->id }}-form">Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    {{ $orders->links() }}
  </div>
</div>
@else
<div class="row">
  <div class="col-12">
    <h1 class="text-center"><a href="{{ route('order.create') }}">Create first order!</a></h1>
  </div>
</div>
@endif
@if(count($deleteOrders))

<div class="row mt-5">
  <div class="col-12">
    <h1 class="text-center text-danger">Orders deleted</h1>
  </div>
  <div class="col-md-12">
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Contract with
          </th>
          <th scope="col">Tags</th>
          <th scope="col" colspan="2" class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($deleteOrders as $deleteOrder)
        <tr>
          <th scope="row">{{ $deleteOrder->id }}</th>
          <td>{{ $deleteOrder->title }}</td>
          <td>{{ $deleteOrder->description }}</td>
          <td>
            @if($deleteOrder->contractName != '')
            {{ $deleteOrder->contractName }}
            @elseif($deleteOrder->contractNameDeleted != '')
            {{ $deleteOrder->contractNameDeleted }}
            <span>
              <a href="#" class="text-danger"
                onclick="event.preventDefault(); document.getElementById('restore-customer-{{ $deleteOrder->customerIdDeleted }}-form').click();">(Restore
                customer)</a>
            </span>
            <span class="d-none">
              <form action="{{ route('customer.restore', $deleteOrder->customerIdDeleted) }}" method="POST">
                @method('POST')
                @csrf
                <button type="submit" id="restore-customer-{{ $deleteOrder->customerIdDeleted }}-form">Restore</button>
              </form>
            </span>
          </td>
          @endif
          </td>
          <td>{{ $deleteOrder->tagsName }}</td>
          <td><a href="#"
              onclick="event.preventDefault(); document.getElementById('restore-order-{{ $deleteOrder->id }}-form').click();">[Restore]</a>
          </td>
          <td style="display: none;">
            <form action="{{ route('order.restore', $deleteOrder->id) }}" method="POST">
              @method('POST')
              @csrf
              <button type="submit" id="restore-order-{{ $deleteOrder->id }}-form">Restore</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@endif


@endsection