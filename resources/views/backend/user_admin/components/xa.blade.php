<label>Phường <span id="xa_span" style="color: red">(*)</span></label>
<select name="xa_id" id="xa_id" class="form-control" required oninvalid="this.setCustomValidity('Vui lòng chọn phường')" oninput="this.setCustomValidity('')">
    <option value="">--- Chọn phường ---</option>
    @foreach($xas as $xa)
        <option value="{{ $xa->id }}">{{ $xa->name }}</option>
    @endforeach
</select>

<script>
    $('#xa_id').select2();
</script>
