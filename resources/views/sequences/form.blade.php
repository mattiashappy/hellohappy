<div class="form-group row">
    <label class="col-md-3 col-form-label" for="name">{{ __('Sequence Name') }}</label>
    <div class="col-md-7">
        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $sequence->name ?? '') }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label" for="tag_id">{{ __('Trigger Tag') }}</label>
    <div class="col-md-7">
        <select id="tag_id" name="tag_id" class="form-control">
            @foreach($tags as $id => $name)
                <option value="{{ $id }}" {{ (old('tag_id', $selectedTagId ?? null) == $id) ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label" for="is_active">{{ __('Active') }}</label>
    <div class="col-md-7">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $sequence->is_active ?? false) ? 'checked' : '' }}>
    </div>
</div>
