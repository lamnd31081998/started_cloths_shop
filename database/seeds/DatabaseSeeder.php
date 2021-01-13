<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //insert admin account
        DB::table('users')->insert([
            'username' => 'lam',
            'password' => bcrypt('123456'),
            'email' => 'lamnd31081998@gmail.com',
            'name' => 'Nguyễn Lâm',
            'phone_number' => '0123456789',
            'tinh_id' => 1,
            'huyen_id' => 1,
            'xa_id' => 1,
            'address' => '111 Thanh Lân',
            'gender' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('users')->insert([
            'username' => 'thien',
            'password' => bcrypt('123456'),
            'email' => 'thien@gmail.com',
            'name' => 'Nguyễn Thiện',
            'phone_number' => '0123456789',
            'tinh_id' => 1,
            'huyen_id' => 1,
            'xa_id' => 1,
            'gender' => 1,
            'address' => 'Trúc bạch',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('users')->insert([
            'username' => 'trunganh',
            'password' => bcrypt('123456'),
            'email' => 'trunganh@gmail.com',
            'name' => 'Trung Anh',
            'phone_number' => '0123456789',
            'tinh_id' => 1,
            'huyen_id' => 1,
            'xa_id' => 1,
            'address' => 'Linh Đàm',
            'gender' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('users')->insert([
            'username' => 'duy',
            'password' => bcrypt('123456'),
            'email' => 'duy@gmail.com',
            'name' => 'Duy Phạm',
            'phone_number' => '0123456789',
            'tinh_id' => 1,
            'huyen_id' => 1,
            'xa_id' => 1,
            'address' => 'Cầu giấy',
            'gender' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert admin account but locked
        DB::table('users')->insert([
            'username' => 'lam_admin_khoatk',
            'password' => bcrypt('123456'),
            'email' => 'lam_admin_khoatk@gmail.com',
            'name' => 'Nguyễn Lâm',
            'phone_number' => '0123456789',
            'tinh_id' => 1,
            'huyen_id' => 1,
            'xa_id' => 1,
            'address' => '111 Thanh Lân',
            'gender' => 1,
            'status' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert attributes color
        DB::table('attributes')->insert([
            'name' => 'Màu sắc',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert attributes size
        DB::table('attributes')->insert([
            'name' => 'Kích cỡ',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert category
        DB::table('categories')->insert([
           'name' => 'Đồ nam',
           'slug' => 'do-nam',
           'thumb' => 'images/default_generals/categories-thumb/thumb.jpg',
           'parent_id' => 0,
           'created_at' => Carbon::now(),
           'updated_at' => Carbon::now()
        ]);

        //insert category
        DB::table('categories')->insert([
            'name' => 'Đồ nữ',
            'slug' => 'do-nu',
            'thumb' => 'images/default_generals/categories-thumb/thumb.jpg',
            'parent_id' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert category
        DB::table('categories')->insert([
            'name' => 'Quần nam',
            'slug' => 'quan-nam',
            'parent_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert category
        DB::table('categories')->insert([
            'name' => 'Áo nam',
            'slug' => 'ao-nam',
            'parent_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert category
        DB::table('categories')->insert([
            'name' => 'Quần nữ',
            'slug' => 'quan-nu',
            'parent_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert category
        DB::table('categories')->insert([
            'name' => 'Áo nữ',
            'slug' => 'ao-nu',
            'parent_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert color values for quan-nam
        DB::table('attribute_values')->insert([
            'attribute_id' => 1,
            'category_id' => 3,
            'value' => 'Đen',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 1,
            'category_id' => 3,
            'value' => 'Đỏ',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 1,
            'category_id' => 3,
            'value' => 'Xanh lá cây',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert size values for quan-nam
        DB::table('attribute_values')->insert([
            'attribute_id' => 2,
            'category_id' => 3,
            'value' => 'S',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 2,
            'category_id' => 3,
            'value' => 'M',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 2,
            'category_id' => 3,
            'value' => 'L',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert color values for ao-nam
        DB::table('attribute_values')->insert([
            'attribute_id' => 1,
            'category_id' => 4,
            'value' => 'Đen',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 1,
            'category_id' => 4,
            'value' => 'Đỏ',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 1,
            'category_id' => 4,
            'value' => 'Xanh lá cây',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert size values for ao-nam
        DB::table('attribute_values')->insert([
            'attribute_id' => 2,
            'category_id' => 4,
            'value' => 'S',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 2,
            'category_id' => 4,
            'value' => 'M',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 2,
            'category_id' => 4,
            'value' => 'L',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert color values for quan-nu
        DB::table('attribute_values')->insert([
            'attribute_id' => 1,
            'category_id' => 5,
            'value' => 'Hồng',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 1,
            'category_id' => 5,
            'value' => 'Tím',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 1,
            'category_id' => 5,
            'value' => 'Mận',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert size values for quan-nu
        DB::table('attribute_values')->insert([
            'attribute_id' => 2,
            'category_id' => 5,
            'value' => 'XS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 2,
            'category_id' => 5,
            'value' => 'S',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 2,
            'category_id' => 5,
            'value' => 'M',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert color values for ao-nu
        DB::table('attribute_values')->insert([
            'attribute_id' => 1,
            'category_id' => 6,
            'value' => 'Hồng',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 1,
            'category_id' => 6,
            'value' => 'Tím',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 1,
            'category_id' => 6,
            'value' => 'Mận',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //insert size values for ao-nu
        DB::table('attribute_values')->insert([
            'attribute_id' => 2,
            'category_id' => 6,
            'value' => 'XS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 2,
            'category_id' => 6,
            'value' => 'S',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('attribute_values')->insert([
            'attribute_id' => 2,
            'category_id' => 6,
            'value' => 'M',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //Tạo các loại hình giao hàng
        DB::table('shipfees')->insert([
            'name' => 'Đến lấy tại cửa hàng',
            'description' => '',
            'price' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('shipfees')->insert([
            'name' => 'Giao hàng nội thành',
            'description' => '2-3 ngày',
            'price' => 25000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('shipfees')->insert([
            'name' => 'Giao hàng nội thành siêu tốc',
            'description' => 'Trong vòng 24h',
            'price' => 30000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('shipfees')->insert([
            'name' => 'Giao hàng ngoại thành',
            'description' => '4-5 ngày',
            'price' => 40000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //Create promotion code
        DB::table('promotions')->insert([
            'code' => 'giam20',
            'description' => 'Giảm 20% đơn hàng',
            'discount' => 20,
            'max_use' => 10,
            'max_user_use' => 1,
            'expire_time' => date('Ymd', strtotime('+15 days')),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('promotions')->insert([
            'code' => 'giam50',
            'description' => 'Giảm 50% đơn hàng',
            'discount' => 50,
            'at_least' => '500000',
            'max_use' => 10,
            'max_user_use' => 1,
            'expire_time' => date('Ymd', strtotime('+15 days')),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //Create default home slide images
        DB::table('generals')->insert([
            'type' => 'home-images',
            'image_name' => 'image_1.jpg',
            'images' => 'images/default_generals/home-images/image_1.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('generals')->insert([
            'type' => 'home-images',
            'image_name' => 'image_2.jpg',
            'images' => 'images/default_generals/home-images/image_2.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('generals')->insert([
            'type' => 'home-images',
            'image_name' => 'image_3.jpg',
            'images' => 'images/default_generals/home-images/image_3.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //Create default cart - checkout - trackingorder thumb
        DB::table('generals')->insert([
            'type' => 'cart-thumb',
            'image_name' => 'cart.jpg',
            'images' => 'images/default_generals/cart-checkout-trackingorder-thumb/cart.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('generals')->insert([
            'type' => 'checkout-thumb',
            'image_name' => 'checkout.jpg',
            'images' => 'images/default_generals/cart-checkout-trackingorder-thumb/checkout.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('generals')->insert([
            'type' => 'trackingorder-thumb',
            'image_name' => 'trackingorder.jpg',
            'images' => 'images/default_generals/cart-checkout-trackingorder-thumb/trackingorder.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //Create default fast links
        DB::table('generals')->insert([
            'type' => 'fast-link',
            'name' => 'trackingorder',
            'description' => 'Tra cứu đơn hàng',
            'link' => 'https://doantmdt.site/tra-cuu-don-hang',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('generals')->insert([
            'type' => 'fast-link',
            'name' => 'terms-and-security',
            'description' => 'Điều khoản và bảo mật',
            'link' => 'https://doantmdt.site/dieu-khoan-va-bao-mat',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('generals')->insert([
            'type' => 'fast-link',
            'name' => 'checkout-terms',
            'description' => 'Chính sách thanh toán',
            'link' => 'https://doantmdt.site/chinh-sach-thanh-toan',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('generals')->insert([
            'type' => 'fast-link',
            'name' => 'shipping_terms',
            'description' => 'Chính sách giao hàng',
            'link' => 'https://doantmdt.site/chinh-sach-giao-hang',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('generals')->insert([
            'type' => 'fast-link',
            'name' => 'return_terms',
            'description' => 'Chính sách bảo hành và dổi trả',
            'link' => 'https://doantmdt.site/chinh-sach-bao-hanh-va-doi-tra',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //Create default contact
        DB::table('generals')->insert([
            'type' => 'contact',
            'link' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1862.3184568183208!2d105.82372685819402!3d21.007186750440997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ac8109765ba5%3A0xb3be79f8f64a59f9!2zMTc1IFTDonkgU8ahbiwgVHJ1bmcgTGnhu4d0LCDEkOG7kW5nIMSQYSwgSMOgIE7hu5lpLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1601968320951!5m2!1svi!2s" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>',
            'name' => 'aStar',
            'address' => '175 Tây Sơn',
            'tinh_id' => 1,
            'huyen_id' => 6,
            'xa_id' => 214,
            'description' => 'aStar 175 Tây Sơn',
            'phone_number' => '0123456789',
            'email' => 'lamnd31081998@gmail.com',
            'images' => 'images/default_generals/shop-logo/logo.png',
            'small_images' => 'images/default_generals/shop-small-logo/small_logo.png',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //Create default social links
        DB::table('generals')->insert([
            'type' => 'social-link',
            'name' => 'Facebook',
            'link' => '#',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('generals')->insert([
            'type' => 'social-link',
            'name' => 'Instagram',
            'link' => '#',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('generals')->insert([
            'type' => 'social-link',
            'name' => 'Twitter',
            'link' => '#',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('generals')->insert([
            'type' => 'social-link',
            'name' => 'Youtube',
            'link' => '#',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //Create about us data
        DB::table('generals')->insert([
            'type' => 'about-us',
            'images' => 'images/default_generals/about-us-thumb/thumb.jpg',
            'description' => 'Về chúng tôi',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //Create contact us data
        DB::table('generals')->insert([
            'type' => 'contact-us',
            'images' => 'images/default_generals/contact-us-thumb/thumb.jpg',
            'link' => '<iframe src="https://docs.google.com/forms/d/e/1FAIpQLSeisZNRaGEr45Ty5rCzZetVsuak_u1lV483CcPEmpYB429M9w/viewform?embedded=true" width="100%" height="1150" frameborder="0" marginheight="0" marginwidth="0">Đang tải…</iframe>',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //Create terms and security data
        DB::table('generals')->insert([
            'type' => 'terms-and-security',
            'description' => 'Điều khoản và bảo mật',
            'images' => 'images/default_generals/terms-and-security-thumb/thumb.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //Create shipping terms data
        DB::table('generals')->insert([
            'type' => 'shipping-terms',
            'description' => 'Chính sách giao hàng',
            'images' => 'images/default_generals/shipping-terms-thumb/thumb.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //Create return terms data
        DB::table('generals')->insert([
            'type' => 'return-terms',
            'description' => 'Chính sách đổi trả và bảo hành',
            'images' => 'images/default_generals/return-terms-thumb/thumb.jpg',
            'created_at' =>Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        //Create checkout terms data
        DB::table('generals')->insert([
            'type' => 'checkout-terms',
            'description' => 'Chính sách thanh toán',
            'images' => 'images/default_generals/checkout-terms-thumb/thumb.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
