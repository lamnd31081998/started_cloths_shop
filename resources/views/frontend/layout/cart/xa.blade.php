<!-- Quan -->
<label for="xa_id">Phường <span style="color: red">(*)</span></label>
<select name="xa_id" id="xa_id" class="dropdown_item_select checkout_input" required oninvalid="this.setCustomValidity('Vui lòng chọn thành phố')" oninput="this.setCustomValidity('')">
    <option value="">--- Phường ---</option>
    @foreach($xas as $xa)
        <option value="{{ $xa->id }}">{{ $xa->name }}</option>
    @endforeach
</select>

<script>
    $('#xa_id').select2({
        theme: "bootstrap"
    });
</script>
