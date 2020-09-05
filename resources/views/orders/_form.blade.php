<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Title</label>
      <input type="text" name="title" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{old('title', $order->title)}}">
      @if ($errors->has('title'))
      <span class="invalid-feedback d-block">
        <strong>{{ $errors->first('title') }}</strong>
      </span>
      @endif
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Description</label>
      <input type="text" name="description" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" value="{{old('description', $order->description)}}">
      @if ($errors->has('description'))
      <span class="invalid-feedback d-block">
        <strong>{{ $errors->first('description') }}</strong>
      </span>
      @endif
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Cost</label>
      <input value="{{old('cost', $order->cost)}}" type="number" name="cost" class="form-control {{ $errors->has('cost') ? ' is-invalid' : '' }}" step="0.01" min="0"
        max="99999999">
        @if ($errors->has('cost'))
        <span class="invalid-feedback d-block">
          <strong>{{ $errors->first('cost') }}</strong>
        </span>
        @endif
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Choose a customer</label>
      <select class="form-control {{ $errors->has('customer_id') ? ' is-invalid' : '' }}" name="customer_id">
        <option value="" data-display-text="Select customer">Select customer</option>
        @if($customers->count())
        @foreach($customers as $customer)
        <option @if($order->customer_id)
          {{$customer->id == $order->customer_id ? 'selected' : ''}}
          @endif
          value="{{ $customer->id }}">{{ $customer->first_name }}</option>
        @endforeach
        @endif
      </select>
      @if ($errors->has('customer_id'))
        <span class="invalid-feedback d-block">
          <strong>{{ $errors->first('customer_id') }}</strong>
        </span>
        @endif
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Tags</label>
      <select class="form-control" name="tags[]" multiple>
        @if($tags->count())
        @foreach($tags as $tag)
        <option @if(isset($selectedTags)) @foreach($selectedTags as $tagSelect) @if($tagSelect->id == $tag->id)
          selected
          @endif
          @endforeach
          @endif
          value="{{ $tag->id }}">{{ $tag->name }}
        </option>
        @endforeach
        @endif
      </select>
    </div>
  </div>
</div>