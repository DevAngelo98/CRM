<div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label>First Name</label>
          <input type="text" name="first_name" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" value="{{ old('first_name', $customer->first_name) }}">
          @if ($errors->has('first_name'))
        <span class="invalid-feedback d-block">
          <strong>{{ $errors->first('first_name') }}</strong>
        </span>
        @endif
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label>Last Name</label>
          <input type="text" name="last_name" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" value="{{ old('last_name', $customer->last_name) }}">
          @if ($errors->has('last_name'))
        <span class="invalid-feedback d-block">
          <strong>{{ $errors->first('last_name') }}</strong>
        </span>
        @endif
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email', $customer->email) }}">
          @if ($errors->has('email'))
        <span class="invalid-feedback d-block">
          <strong>{{ $errors->first('email') }}</strong>
        </span>
        @endif
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label>Phone number</label>
          <input type="text" name="phone" class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone', $customer->phone) }}">
          @if ($errors->has('phone'))
        <span class="invalid-feedback d-block">
          <strong>{{ $errors->first('phone') }}</strong>
        </span>
        @endif
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label>Company</label>
          <input type="text" name="company" class="form-control {{ $errors->has('company') ? ' is-invalid' : '' }}" value="{{ old('company', $customer->company) }}">
          @if ($errors->has('company'))
        <span class="invalid-feedback d-block">
          <strong>{{ $errors->first('company') }}</strong>
        </span>
        @endif
        </div>
      </div>
    </div>
