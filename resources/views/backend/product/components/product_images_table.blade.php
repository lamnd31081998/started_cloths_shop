@if ($total_images == 0)
    <tr>
        <td id="no-data" colspan="4">Không có dữ liệu</td>
    </tr>
@else
    @for($i = 0; $i < $total_images; $i++)
        <tr>
            <input type="hidden" id="uploaded_image_{{ $i+1 }}" name="uploaded_images[]" value="{{ $uploaded_images[$i] }}">
            <td>{{ $i+1 }}</td>
            <td>
                <img class="img-fluid" id="preview_image_{{ $i+1 }}" src="{{ asset($uploaded_images[$i]) }}" style="width: 300px; height: 300px">
            </td>
            <td id="image_src_{{ $i+1 }}">{{ $uploaded_images[$i] }}</td>
            <td>
                <button title="Thay đổi ảnh" style="margin-right: 5px" type="button" class="btn btn-primary" onclick="edit_image({{ $i+1 }})"><i class="fas fa-edit"></i></button>
                <button title="Gỡ ảnh" type="button" class="btn btn-danger" onclick="remove_image({{ $i+1 }})"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    @endfor
@endif
