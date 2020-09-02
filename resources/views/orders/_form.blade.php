<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Title</label>
      <input required type="text" name="title" class="form-control" value="{{old('title', $order->title)}}">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Description</label>
      <input required type="text" name="description" class="form-control" value="{{old('description', $order->description)}}">
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Cost</label>
      <input required value="{{old('cost', $order->cost)}}" type="number" name="cost" class="form-control" step="0.01" min="0" max="99999999">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Update customer</label>
      <select required class="form-control" name="customer_id">
        <option value="" data-display-text="Select customer">Select customer</option>
        @if($customers->count())
          @foreach($customers as $customer)
          <option 
          @if($order->customer_id) 
          {{$customer->id == $order->customer_id ? 'selected' : ''}} 
          @endif
          value="{{ $customer->id }}">{{ $customer->first_name }}</option>
        @endforeach
        @endif
      </select>
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
            <option 
              @if(isset($selectedTags)) 
                @foreach($selectedTags as $tagSelect) 
                  @if($tagSelect->id == $tag->id)
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