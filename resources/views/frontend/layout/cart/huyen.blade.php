<!-- Quan -->
<label for="huyen_id">Quận <span style="color: red">(*)</span></label>
<select onchange="getXa()" name="huyen_id" id="huyen_id" class="dropdown_item_select checkout_input" required oninvalid="this.setCustomValidity('Vui lòng chọn thành phố')" oninput="this.setCustomValidity('')">
    <option value="">--- Quận ---</option>
    @foreach($huyens as $huyen)
        <option value="{{ $huyen->id }}">{{ $huyen->name }}</option>
    @endforeach
</select>

<script>
    $('#huyen_id').select2({
        theme: "bootstrap"
    });
</script>
