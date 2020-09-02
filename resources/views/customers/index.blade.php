@extends('layouts.app')

@section('content')

@if(count($customers))

<div class="row">
  <div class="offset-md-10 col-md-2">
    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-block">+ New Customer</a>
  </div>
</div>
<br>
<div class="row">
  <div class="col-12">
    <h1 class="text-center text-success">Active customers</h1>
  </div>
  <div class="col-md-12">
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">First</th>
          <th scope="col">Last</th>
          <th scope="col">Email</th>
          <th scope="col">Phone</th>
          <th scope="col">Company</th>
          <th scope="col" colspan="2" class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($customers as $customer)
        <tr>
          <th scope="row">{{ $customer->id }}</th>
          <td>{{ $customer->first_name }}</td>
          <td>{{ $customer->last_name }}</td>
          <td>{{ $customer->email }}</td>
          <td>{{ $customer->phone }}</td>
          <td>{{ $customer->company }}</td>
          <td><a href="{{ route('customers.edit', $customer) }}">[Edit]</a></td>
          <td><a href="#" onclick="event.preventDefault(); document.getElementById('delete-customer-{{ $customer->id }}-form').click();">[Delete]</a>
          </td>
          <td style="display: none;">
            <form action="{{ route('customers.destroy', $customer) }}"
            method="POST" >
            @method('DELETE')
            @csrf
            <button type="submit" id="delete-customer-{{ $customer->id }}-form">Delete</button>
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
    {{ $customers->links() }}
  </div>
</div>
@else
<div class="row">
  <div class="col-12">
    <h1 class="text-center"><a href="{{ route('customers.create') }}" >Create first customer!</a></h1>
  </div>
</div>
@endif

@if(count($deleteCustomers))

<div class="row mt-5">
  <div class="col-12">
    <h1 class="text-center text-danger">Customers deleted</h1>
  </div>
  <div class="col-md-12">
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">First</th>
          <th scope="col">Last</th>
          <th scope="col">Email</th>
          <th scope="col">Phone</th>
          <th scope="col">Company</th>
          <th scope="col" colspan="2" class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($deleteCustomers as $deleteCustomer)
        <tr>
          <th scope="row">{{ $deleteCustomer->id }}</th>
          <td>{{ $deleteCustomer->first_name }}</td>
          <td>{{ $deleteCustomer->last_name }}</td>
          <td>{{ $deleteCustomer->email }}</td>
          <td>{{ $deleteCustomer->phone }}</td>
          <td>{{ $deleteCustomer->company }}</td>
          <td><a href="#" onclick="event.preventDefault(); document.getElementById('restore-customer-{{ $deleteCustomer->id }}-form').click();">[Restore]</a>
          </td>
          <td style="display: none;">
            <form action="{{ route('customer.restore', $deleteCustomer->id) }}"
            method="POST" >
            @method('POST')
            @csrf
            <button type="submit" id="restore-customer-{{ $deleteCustomer->id }}-form">Restore</button>
          </form>
          </td>
          
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@endif

@stop