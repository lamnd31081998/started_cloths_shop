<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\General;
use App\Models\Huyen;
use App\Models\Tinh;
use App\Models\Xa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    public function edit_home_images()
    {
        $home_images = General::getHomeImages();
        return view('backend.general.home_images')->with(['home_images' => $home_images]);
    }

    public function home_image_table(Request $request)
    {
        $uploaded_images = [];
        $content_images = [];
        if (isset($request->uploaded_images)) {
            foreach ($request->uploaded_images as $index=>$image) {
                $uploaded_images[] = $image;
                $content_images[] = $request->uploaded_contents[$index];
            }
        }
        return response()->json([
            'new_total_images' => count($uploaded_images)+1,
            'images_uploaded' => $uploaded_images,
            'uploaded_contents' => $content_images,
            'view' => view('backend.general.components.home_images.uploaded_image_table')->with(['total_images' => count($uploaded_images), 'uploaded_images' => $uploaded_images, 'uploaded_contents' => $content_images])->render()
        ], 200);
    }

    public function update_home_images(Request $request)
    {
        General::where('type', 'home-images')->delete();
        foreach ($request->uploaded_images as $index=>$image) {
            $home_image = new General();
            $home_image->description = $request->content_images[$index];
            $home_image->type = 'home-images';
            $home_image->images = $image;
            $home_image->save();
        }

        echo '<script>';
        echo 'alert("Cập nhật ảnh trang chủ thành công");';
        echo 'window.location.href="'.route('be.general.edit_home_images').'";';
        echo '</script>';
    }

    public static function edit_cart_checkout_trackingorder_thumb()
    {
        $cart_image = DB::table('generals')->where('type', '=', 'thumb-cart')->first();
        $checkout_image = DB::table('generals')->where('type', '=', 'thumb-checkout')->first();
        $trackingorder_image = DB::table('generals')->where('type', '=', 'thumb-trackingorder')->first();
        return view('backend.general.cart_checkout_trackingorder_thumb')->with(['cart_image' => $cart_image, 'checkout_image' => $checkout_image, 'trackingorder_image' => $trackingorder_image]);
    }

    public static function update_cart_checkout_trackingorder_thumb(Request $request)
    {
        if (isset($request->cart)) {
            $thumb_explode = explode('/', $request->cart);
            $thumb_name = end($thumb_explode);
            DB::table('generals')->where('type', '=', 'cart-thumb')->update([
                'image_name' => $thumb_name,
                'images' => $request->cart,
                'updated_at' => Carbon::now()
            ]);
        }

        if (isset($request->checkout)) {
            $thumb_explode = explode('/', $request->checkout);
            $thumb_name = end($thumb_explode);
            DB::table('generals')->where('type', '=', 'checkout-thumb')->update([
                'image_name' => $thumb_name,
                'images' => $request->checkout,
                'updated_at' => Carbon::now()
            ]);
        }

        if (isset($request->trackingorder)) {
            $thumb_explode = explode('/', $request->trackingorder);
            $thumb_name = end($thumb_explode);
            DB::table('generals')->where('type', '=', 'trackingorder-thumb')->update([
                'image_name' => $thumb_name,
                'images' => $request->trackingorder,
                'updated_at' => Carbon::now()
            ]);
        }

        echo '<script>';
        echo 'alert("Cập nhật ảnh thành công");';
        echo 'window.location.href="'.route('be.general.edit_cart_checkout_trackingorder_thumb').'";';
        echo '</script>';
    }

    public function edit_fast_links()
    {
        $fast_links = DB::table('generals')->where('type', '=', 'fast-link')->get();
        return view('backend.general.fast_links')->with(['fast_links' => $fast_links]);
    }

    public static function delete_fast_links(Request $request)
    {
        General::destroy($request->fast_link_id);
        return response()->json([
        ], 200);
    }

    public function update_fast_links(Request $request)
    {
        if (isset($request->link)) {
            $links = $request->link;
        }
        else {
            $links = [];
        }

        DB::table('generals')->where('type', '=', 'fast-link')->where('name', '=', null)->delete();
        foreach ($links as $index=>$link) {
            DB::table('generals')->insert([
                'type' => 'fast-link',
                'description' => $request->description[$index],
                'link' => $link,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        echo '<script>';
        echo 'alert("Cập nhật đường dẫn nhanh thành công");';
        echo 'window.location.href="'.route('be.general.edit_fast_links').'";';
        echo '</script>';
    }

    public function edit_shop_information()
    {
        $tinhs = Tinh::getAllTinh();
        $huyens = Huyen::getAllHuyen();
        $xas = Xa::getAllXa();
        $contact = DB::table('generals')->where('type', '=', 'contact')->first();
        return view('backend.general.shop_information')->with(['tinhs' => $tinhs, 'huyens' => $huyens, 'xas' => $xas, 'contact' => $contact]);
    }

    public function get_map(Request $request)
    {
        return response()->json([
            'view' => view('backend.general.components.shop_information.map')->with('map', $request->map)->render()
        ], 200);
    }

    public function update_shop_information(Request $request)
    {
        DB::table('generals')->where('type', '=', 'contact')->update([
            'fb_script' => $request->fb_script,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'description' => $request->description,
            'link' => $request->link,
            'address' => $request->address,
            'tinh_id' => $request->tinh_id,
            'huyen_id' => $request->huyen_id,
            'xa_id' => $request->xa_id,
            'updated_at' => Carbon::now()
        ]);

        if (isset($request->logo)) {
            DB::table('generals')->where('type', '=', 'contact')->update([
               'images' => $request->logo
            ]);
        }

        if (isset($request->small_logo)) {
            DB::table('generals')->where('type', '=', 'contact')->update([
                'small_images' => $request->small_logo
            ]);
        }

        echo '<script>';
        echo 'alert("Cập nhật thông tin cửa hàng thành công");';
        echo 'window.location.href="'.route('be.general.edit_shop_information').'";';
        echo '</script>';
    }

    public function edit_social_links()
    {
        $social_links = DB::table('generals')->where('type', '=', 'social-link')->orderBy('id')->get();
        return view('backend.general.social_links')->with(['social_links' => $social_links]);
    }

    public function update_social_links(Request $request)
    {
        $links_explode = explode(',', $request->links);
        foreach ($links_explode as $link_id) {
            $link_name = 'link_'.$link_id;
            if ($request->$link_name == "") {
                $link = '#';
            }
            else {
                $link = 'https://'.$request->$link_name;
            }
            $links_data[] = ['id' => $link_id ,'link' => $link];
        }
        foreach ($links_data as $link) {
            DB::table('generals')->where('type', '=', 'social-link')->where('id', '=', $link['id'])->update([
                'link' => $link['link'],
                'updated_at' => Carbon::now()
            ]);
        }

        echo '<script>';
        echo 'alert("Cập nhật thông tin mạng xã hội thành công");';
        echo 'window.location.href="'.route('be.general.edit_social_links').'";';
        echo '</script>';
    }

    public function edit_about_us()
    {
        $data = DB::table('generals')->where('type', '=', 'about-us')->first();
        return view('backend.general.about_us')->with(['data' => $data]);
    }

    public function update_about_us(Request $request)
    {

        DB::table('generals')->where('type', '=', 'about-us')->update([
            'description' => $request->description,
            'updated_at' => Carbon::now()
        ]);

        if (isset($request->thumb)) {
            $thumb_explode = explode('/', $request->thumb);
            $thumb_name = end($thumb_explode);
            DB::table('generals')->where('type', '=', 'about-us')->update([
               'images' => $request->thumb,
               'image_name' => $thumb_name,
               'updated_at' => Carbon::now()
            ]);
        }

        echo '<script>';
        echo 'alert("Cập nhật giới thiệu cửa hàng thành công");';
        echo 'window.location.href="'.route('be.general.edit_about_us').'";';
        echo '</script>';
    }

    public function edit_contact_us()
    {
        $data = DB::table('generals')->where('type', '=', 'contact-us')->first();
        return view('backend.general.contact_us')->with(['data' => $data]);
    }

    public function get_docs(Request $request)
    {
        return response()->json([
            'view' => view('backend.general.components.contact_us.docs')->with('docs', $request->docs)->render()
        ], 200);

    }

    public function update_contact_us(Request $request)
    {
        General::where('type', 'contact-us')->update([
            'images' => $request->thumb,
            'link' => $request->link,
            'updated_at' => Carbon::now()
        ]);

        echo '<script>';
        echo 'alert("Cập nhật biểu mẫu thành công");';
        echo 'window.location.href="'.route('be.general.edit_contact_us').'";';
        echo '</script>';
    }

    public function edit_terms_and_security()
    {
        $data = DB::table('generals')->where('type', '=', 'terms-and-security')->first();
        return view('backend.general.terms_and_security')->with(['data' => $data]);
    }

    public function update_terms_and_security(Request $request)
    {
        DB::table('generals')->where('type', '=', 'terms-and-security')->update([
            'images' => $request->thumb,
            'description' => $request->description,
            'updated_at' => Carbon::now()
        ]);

        echo '<script>';
        echo 'alert("Cập nhật điều khoản và bảo mật thành công");';
        echo 'window.location.href="'.route('be.general.edit_terms_and_security').'";';
        echo '</script>';
    }

    public function edit_shipping_terms()
    {
        $data = DB::table('generals')->where('type', '=', 'shipping-terms')->first();
        return view('backend.general.shipping_terms')->with('data', $data);
    }

    public function update_shipping_terms(Request $request)
    {
        General::where('type', 'shipping-terms')->update([
            'images' => $request->thumb,
            'description' => $request->description,
            'updated_at' => Carbon::now()
        ]);

        echo '<script>';
        echo 'alert("Cập nhật chính sách giao hàng thành công");';
        echo 'window.location.href="'.route('be.general.edit_shipping_terms').'";';
        echo '</script>';
    }

    public function edit_return_terms()
    {
        $data = DB::table('generals')->where('type', '=', 'return-terms')->first();
        return view('backend.general.return_terms')->with('data', $data);
    }

    public function update_return_terms(Request $request)
    {
        General::where('type', 'return-terms')->update([
            'images' => $request->thumb,
            'description' => $request->description,
            'updated_at' => Carbon::now()
        ]);

        echo '<script>';
        echo 'alert("Cập nhật chính sách bảo hành và đổi trả thành công");';
        echo 'window.location.href="'.route('be.general.edit_shipping_terms').'";';
        echo '</script>';
    }

    public function edit_checkout_terms()
    {
        $data = General::where('type', 'checkout-terms')->first();
        return view('backend.general.checkout_terms')->with(['data' => $data]);
    }

    public function update_checkout_terms(Request $request)
    {
        General::where('type', 'checkout-terms')->update([
            'images' => $request->thumb,
            'description' => $request->description,
            'updated_at' => Carbon::now()
        ]);

        echo '<script>';
        echo 'alert("Cập nhật chính sách thanh toán thành công");';
        echo 'window.location.href="'.route('be.general.edit_checkout_terms').'";';
        echo '</script>';
    }
}
