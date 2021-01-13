@if ($total_images == 0)
    <tr>
        <td colspan="5">Không có dữ liệu</td>
    </tr>
@else
    @for ($i = 0; $i < $total_images; $i++)
        <tr>
            <input type="hidden" name="uploaded_images[]" id="uploaded_image_{{ $i+1 }}" value="{{ $uploaded_images[$i] }}">
            <td>{{ $i+1 }}</td>
            <td><img class="img-fluid" style="width: 400px; height: 300px" id="preview_image_{{ $i+1 }}" src="{{ asset($uploaded_images[$i]) }}"></td>
            <td id="image_src_{{ $i+1 }}">{{ $uploaded_images[$i] }}</td>
            <td><textarea onblur="updateContent(this, {{ $i }})" style="height: 200px" type="text" class="form-control" placeholder="Nội dung cho ảnh" id="content_image_{{ $i+1 }}" name="content_images[]">{{ $uploaded_contents[$i] }}</textarea></td>
            <td>
                <button title="Thay đổi ảnh" type="button" class="btn btn-primary" style="margin-right: 5px;" onclick="edit_image({{ $i+1 }})"><i class="fas fa-edit"></i></button>
                <button title="Gỡ ảnh" type="button" class="btn btn-danger" onclick="remove_image({{ $i+1 }})"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    @endfor
@endif
