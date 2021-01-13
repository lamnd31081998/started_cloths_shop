<label>Quận <span id="huyen_span" style="color: red">(*)</span></label>
<select name="huyen_id" id="huyen_id" class="form-control" onchange="getXa()" required oninvalid="this.setCustomValidity('Vui lòng chọn quận')" oninput="this.setCustomValidity('')">
    <option value="">--- Chọn quận ---</option>
    @foreach($huyens as $huyen)
        <option value="{{ $huyen->id }}">{{ $huyen->name }}</option>
    @endforeach
</select>

<script>
    $('#huyen_id').select2();
</script>
