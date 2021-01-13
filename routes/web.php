<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route for frontend
Route::group(['namespace' => 'frontend'], function () {
    //Route for home
    Route::get('/', 'HomeController@index')->name('fe.index');
    Route::get('/get-products', 'HomeController@get_products')->name('fe.index.get_products');

    //Route for send_rating
    Route::post('/gui-danh-gia-san-pham', 'ShopController@store_rating')->name('fe.product.store_rating');

    //Route for tracking orders
    Route::get('/tra-cuu-don-hang', 'HomeController@tracking_orders')->name('fe.index.tracking_orders');
    Route::get('/tra-cuu-don-hang/tim-kiem-theo-so-dien-thoai', 'HomeController@search_orders')->name('fe.index.search_orders');

    //Route for about us
    Route::get('/ve-chung-toi', 'HomeController@about_us')->name('fe.index.about_us');

    //Route for contact us
    Route::get('/lien-he-voi-chung-toi', 'HomeController@contact_us')->name('fe.index.contact_us');

    //Route for terms and security
    Route::get('/dieu-khoan-va-bao-mat', 'HomeController@terms_and_security')->name('fe.index.terms_and_security');

    //Route for shipping terms
    Route::get('/chinh-sach-giao-hang', 'HomeController@shipping_terms')->name('fe.index.shipping_terms');

    //Route for return terms
    Route::get('/chinh-sach-bao-hanh-va-doi-tra', 'HomeController@return_terms')->name('fe.index.return_terms');

    //Route for checkout terms
    Route::get('/chinh-sach-thanh-toan', 'HomeController@checkout_terms')->name('fe.index.checkout_terms');

    //Route for cart view
    Route::get('/gio-hang', 'CartController@index')->name('fe.cart.index');

    //Route for cart_ajax
    Route::post('/gio-hang/them-san-pham', 'CartController@add_product')->name('fe.cart.add_product');
    Route::post('/gio-hang/cap-nhat-gio-hang', 'CartController@update_carts')->name('fe.cart.update');
    Route::post('/gio-hang/cap-nhat-phi-giao-hang', 'CartController@update_ship_fee')->name('fe.cart.update_ship_fee');
    Route::post('/gio-hang/kiem-tra-ma-giam-gia', 'CartController@check_promotion')->name('fe.cart.check_promotion');
    Route::post('/gio-hang/xoa-ma-giam-gia', 'CartController@delete_promotion')->name('fe.cart.delete_promotion');
    Route::post('/gio-hang/xoa-gio-hang', 'CartController@delete_carts')->name('fe.cart.delete_carts');

    //Route for checkout
    Route::get('/thanh-toan', 'CartController@checkout')->name('fe.cart.checkout');
    Route::post('/thanh-toan/xu-ly', 'CartController@store')->name('fe.cart.store');

    //Route for checkout_vnpay
    Route::get('/thanh-toan/thanh-toan-online/vnpay', 'CartController@vnpay')->name('fe.cart.vnpay');
    Route::get('/thanh-toan/thanh-toan-online/return', 'CartController@vnpay_return')->name('fe.cart.vnpay_return');

    //Route for checkout_ajax
    Route::get('/thanh-toan/get-huyen', 'CartController@get_huyen')->name('fe.cart.get_huyen');
    Route::get('/thanh-toan/get-xa', 'CartController@get_xa')->name('fe.cart.get_xa');
    Route::post('/thanh-toan/xoa-ma-giam-gia', 'CartController@delete_promotion_checkout')->name('fe.cart.delete_promotion_checkout');
    Route::post('thanh-toan/kiem-tra-ma-giam-gia', 'CartController@checkout_promotion')->name('fe.cart.checkout_promotion');

    //Route for men and women
    Route::get('/{category_slug}', 'ShopController@index')->name('fe.category.index');
    Route::get('/category/get-products', 'ShopController@get_products')->name('fe.category.get_products');

    //Route for details specific products
    Route::get('/{category_dad_slug}/{category_slug}/{product_slug}', 'ShopController@detail')->name('fe.product.detail');
    Route::get('/get-sizes/get-sizes/get-sizes/get-sizes', 'ShopController@get_sizes')->name('fe.product.get_sizes');
});

//Route for login
Route::get('/backend/backend/login/index', 'backend\LoginController@index')->name('be.login.index');
Route::post('/backend/backend/login/auth', 'backend\LoginController@auth')->name('be.login.auth');

//Route for backend
Route::group(['prefix' => 'backend/backend', 'namespace' => 'backend', 'middleware' => 'checklogin'], function () {
    //Route for home
    Route::get('/home/index', 'HomeController@index')->name('be.index');
    Route::get('/home/get-sales-data', 'HomeController@get_sales_data')->name('be.index.get_sales_data');

    //Route for edit Auth information
    Route::get('/self-information/edit', 'HomeController@edit_self_information')->name('be.index.edit_self_information');
    Route::put('/self-information/update', 'HomeController@update_self_information')->name('be.index.update_self_information');

    //Route for edit password
    Route::put('/self-information/update-self-password', 'HomeController@self_update_password')->name('be.index.update_self_password');

    //Route for logout
    Route::get('/admin/logout', 'HomeController@logout')->name('be.logout');

    //Route for admin account list
    Route::get('/admin/index', 'UserController@admin_index')->name('be.admin.index');

    //Route for get data
    Route::post('/all/get-huyen', 'UserController@get_huyen')->name('be.get_huyen');
    Route::post('/all/get-xa', 'UserController@get_xa')->name('be.get_xa');

    //Route for change status
    Route::post('/admin/edit-status', 'UserController@edit_status')->name('be.admin.edit_status');

    //Route for create admin account
    Route::get('/admin/create', 'UserController@admin_create')->name('be.admin.create');
    Route::post('/admin/store', 'UserController@admin_store')->name('be.admin.store');

    //Route for edit admin account
    Route::get('/admin/edit/{id}', 'UserController@admin_edit')->name('be.admin.edit');
    Route::put('/admin/update/{id}', 'UserController@admin_update')->name('be.admin.update');

    //Route for delete admin account
    Route::delete('/admin/delete', 'UserController@admin_destroy')->name('be.admin.delete');

    //Route for category list
    Route::get('/category/index', 'CategoryController@index')->name('be.category.index');

    //Route for get slug
    Route::get('/category/get-slug', 'CategoryController@get_slug')->name('be.category.get_slug');

    //Route for create category
    Route::post('/category/store', 'CategoryController@store')->name('be.category.store');

    //Route for edit category
    Route::post('/category/update', 'CategoryController@update')->name('be.category.update');
    /*Route::put('category/update/{id}', 'CategoryController@update')->name('be.category.update');*/

    //Route for delete category
    Route::delete('/category/delete', 'CategoryController@destroy')->name('be.category.delete');

    //Route for attribute list
    Route::get('/attribute/index', 'AttributeController@index')->name('be.attribute.index');

    //Route for attribute values list of color attribute
    Route::get('/attribute-value/color/index', 'AttributevalueController@color_index')->name('be.attribute_value.attribute_color.index');

    //Route for create attribute values of color attribute
    Route::post('/attribute-value/color/store', 'AttributevalueController@color_store')->name('be.attribute_value.attribute_color.store');

    //Route for delete attribute value of color attribute
    Route::delete('/attribute-value/color/delete', 'AttributevalueController@color_destroy')->name('be.attribute_value.attribute_color.delete');

    //Route for attribute values list of size attribute
    Route::get('/attribute-value/size/index', 'AttributevalueController@size_index')->name('be.attribute_value.attribute_size.index');

    //Route for create attribute values of size attribute
    Route::post('/attribute-value/size/store', 'AttributevalueController@size_store')->name('be.attribute_value.attribute_size.store');

    //Route for delete attribute value of size attribute
    Route::delete('/attribute-value/size/delete', 'AttributevalueController@size_destroy')->name('be.attribute_value.attribute_size.delete');

    //Route for products
    Route::get('/product/index', 'ProductController@index')->name('be.product.index');

    //Route optional for product
    Route::get('/product/get-slug', 'ProductController@get_slug')->name('be.product.get_slug');
    Route::get('/product/get-attribute-values', 'ProductController@get_attribute_values')->name('be.product.get_attribute_values');
    Route::get('/product/create-variants', 'ProductController@create_variants')->name('be.product.create_variants');
    Route::get('/product/preview-product', 'ProductController@preview_product')->name('be.product.preview_product');
    Route::get('/product/return-origin-variants', 'ProductController@return_origin_variants')->name('be.product.return_origin_variants');
    Route::get('/product/product-image-table', 'ProductController@product_image_table')->name('be.product.product_image_table');


    //Route for create products
    Route::get('/product/create', 'ProductController@create')->name('be.product.create');
    Route::post('/product/store', 'ProductController@store')->name('be.product.store');

    //Route for edit products
    Route::get('/product/edit/{id}', 'ProductController@edit')->name('be.product.edit');
    Route::put('/product/update/{id}', 'ProductController@update')->name('be.product.update');

    //Route for delete products
    Route::delete('/product/delete', 'ProductController@destroy')->name('be.product.delete');

    //Route for promotion
    Route::get('/promotion/index', 'PromotionController@index')->name('be.promotion.index');

    //Route check promotion exists
    Route::get('/promotion/check-promotion', 'PromotionController@check_promotion')->name('be.promotion.check_promotion');

    //Route for add promotion
    Route::get('/promotion/create', 'PromotionController@create')->name('be.promotion.create');
    Route::post('/promotion/store', 'PromotionController@store')->name('be.promotion.store');

    //Route for edit promotion
    Route::get('/promotion/edit/{id}', 'PromotionController@edit')->name('be.promotion.edit');
    Route::put('/promotion/update/{id}', 'PromotionController@update')->name('be.promotion.update');

    //Route for ship_fee
    Route::get('/ship_fee/index', 'ShipfeeController@index')->name('be.ship_fee.index');
    Route::put('/ship_fee/update', 'ShipfeeController@update')->name('be.ship_fee.update');

    //Route for order list
    Route::get('/order/index', 'OrderController@index')->name('be.order.index');
    Route::put('/order/update-status', 'OrderController@update_status')->name('be.order.update_status');
    Route::get('/order/get-order-detail', 'OrderController@get_order_detail')->name('be.order.get_order_detail');
    Route::put('/order/cancel', 'OrderController@cancel')->name('be.order.cancel');

    //Route for vnpay refund
    Route::put('/order/vnpay-refund', 'OrderController@vnpay_refund')->name('be.order.vnpay_refund');

    //Route for create order
    Route::get('/order/create', 'OrderController@create')->name('be.order.create');
    Route::post('/order/store', 'OrderController@store')->name('be.order.store');

    //Route ajax for create and update order
    Route::get('/order/add-product', 'OrderController@add_product')->name('be.order.add_product');
    Route::get('/order/get-products', 'OrderController@get_products')->name('be.order.get_products');
    Route::get('/order/get-product-attribute-values', 'OrderController@get_product_attribute_values')->name('be.order.get_product_attribute_values');
    Route::get('/order/update-totals', 'OrderController@update_totals')->name('be.order.update_totals');

    //Route for update order
    Route::get('/order/edit/{id}', 'OrderController@edit')->name('be.order.edit');
    Route::put('/order/update/{id}', 'OrderController@update')->name('be.order.update');

    //Route for general settings
    Route::get('/general-settings/edit-home-images', 'GeneralController@edit_home_images')->name('be.general.edit_home_images');
    Route::get('/general-settings/home-image-table', 'GeneralController@home_image_table')->name('be.general.home_image_table');
    Route::put('/general-settings/update-home-images', 'GeneralController@update_home_images')->name('be.general.update_home_images');

    Route::get('/general-settings/edit-cart-checkout-trackingorder-thumb', 'GeneralController@edit_cart_checkout_trackingorder_thumb')->name('be.general.edit_cart_checkout_trackingorder_thumb');
    Route::put('/general-settings/update-cart-checkout-trackingorder-thumb', 'GeneralController@update_cart_checkout_trackingorder_thumb')->name('be.general.update_cart_checkout_trackingorder_thumb');

    Route::get('/general-settings/edit-fast-links', 'GeneralController@edit_fast_links')->name('be.general.edit_fast_links');
    Route::put('/general-settings/update-fast-links', 'GeneralController@update_fast_links')->name('be.general.update_fast_links');
    Route::delete('/general-settings/delete-fast-links', 'GeneralController@delete_fast_links')->name('be.general.delete_fast_links');

    Route::get('/general-settings/edit-shop-information', 'GeneralController@edit_shop_information')->name('be.general.edit_shop_information');
    Route::get('/general-settings/get-map', 'GeneralController@get_map')->name('be.general.get_map');
    Route::put('/general-settings/update-shop-information', 'GeneralController@update_shop_information')->name('be.general.update_shop_information');

    Route::get('/general-settings/edit-social-links', 'GeneralController@edit_social_links')->name('be.general.edit_social_links');
    Route::put('/general-settings/update-social-links', 'GeneralController@update_social_links')->name('be.general.update_social_links');

    Route::get('/general-settings/edit-about-us', 'GeneralController@edit_about_us')->name('be.general.edit_about_us');
    Route::put('/general-settings/update-about-us', 'GeneralController@update_about_us')->name('be.general.update_about_us');

    Route::get('/general-settings/edit-contact-us', 'GeneralController@edit_contact_us')->name('be.general.edit_contact_us');
    Route::get('/general-settings/get-docs', 'GeneralController@get_docs')->name('be.general.get_docs');
    Route::put('/general-settings/update-contact-us', 'GeneralController@update_contact_us')->name('be.general.update_contact_us');

    Route::get('/general-settings/edit-terms-and-security', 'GeneralController@edit_terms_and_security')->name('be.general.edit_terms_and_security');
    Route::put('/general-settings/update-terms-and-security', 'GeneralController@update_terms_and_security')->name('be.general.update_terms_and_security');

    Route::get('/general-settings/edit-shipping-terms', 'GeneralController@edit_shipping_terms')->name('be.general.edit_shipping_terms');
    Route::put('/general-settings/update-shipping-terms', 'GeneralController@update_shipping_terms')->name('be.general.update_shipping_terms');

    Route::get('/general-settings/edit-return-terms', 'GeneralController@edit_return_terms')->name('be.general.edit_return_terms');
    Route::put('/general-settings/update-return-terms', 'GeneralController@update_return_terms')->name('be.general.update_return_terms');

    Route::get('/general-settings/edit-checkout-terms', 'GeneralController@edit_checkout_terms')->name('be.general.edit_checkout_terms');
    Route::put('/general-settings/update-checkout-terms', 'GeneralController@update_checkout_terms')->name('be.general.update_checkout_terms');
});
