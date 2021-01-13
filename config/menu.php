<?php
return [
    [
        'name' => 'Trang chủ',
        'icon' => 'fas fa-home',
        'route' => 'be.index'
    ],
    [
        'name' => 'Cài đặt chung',
        'icon' => 'fas fa-cog',
        'route' => '#',
        'items' => [
            [
                'name' => 'Ảnh trang chủ',
                'icon' => 'fas fa-circle',
                'route' => 'be.general.edit_home_images',
            ],
            [
                'name' => 'Ảnh giỏ hàng, đơn hàng',
                'icon' => 'fas fa-circle',
                'route' => 'be.general.edit_cart_checkout_trackingorder_thumb',
            ],
            [
                'name' => 'Đường dẫn nhanh',
                'icon' => 'fas fa-circle',
                'route' => 'be.general.edit_fast_links'
            ],
            [
                'name' => 'Thông tin cửa hàng',
                'icon' => 'fas fa-circle',
                'route' => 'be.general.edit_shop_information'
            ],
            [
                'name' => 'Mạng xã hội',
                'icon' => 'fas fa-circle',
                'route' => 'be.general.edit_social_links'
            ],
            [
                'name' => 'Giới thiệu cửa hàng',
                'icon' => 'fas fa-circle',
                'route' => 'be.general.edit_about_us'
            ],
            [
            'name' => 'Biểu mẫu liên hệ',
            'icon' => 'fas fa-circle',
            'route' => 'be.general.edit_contact_us'
            ],
            [
                'name' => 'Điều khoản và bảo mật',
                'icon' => 'fas fa-circle',
                'route' => 'be.general.edit_terms_and_security'
            ],
            [
                'name' => 'Chính sách thanh toán',
                'icon' => 'fas fa-circle',
                'route' => 'be.general.edit_checkout_terms'
            ],
            [
                'name' => 'Chính sách giao hàng',
                'icon' => 'fas fa-circle',
                'route' => 'be.general.edit_shipping_terms'
            ],
            [
                'name' => 'Chính sách đổi trả',
                'icon' => 'fas fa-circle',
                'route' => 'be.general.edit_return_terms'
            ],
        ]
    ],
    [
        'name' => 'Quản lý tài khoản',
        'icon' => 'fas fa-user',
        'route' => '#',
        'items' => [
            [
                'name' => 'Danh sách tài khoản',
                'icon' => 'far fa-circle',
                'route' => 'be.admin.index'
            ],
            [
                'name' => 'Thêm tài khoản',
                'icon' => 'far fa-circle',
                'route' => 'be.admin.create'
            ],
            [
                'name' => 'Sửa tài khoản',
                'icon' => 'far fa-circle',
                'route' => '#'
            ]
        ]
    ],
    [
        'name' => 'Quản lý danh mục',
        'icon' => 'fas fa-tags',
        'route' => 'be.category.index',
    ],
    [
        'name' => 'Quản lý thuộc tính',
        'icon' => 'fas fa-bars',
        'route' => 'be.attribute.index',
    ],
    [
      'name' => 'Giá trị thuộc tính',
      'icon' => 'fas fa-bars',
      'route' => '#',
      'items' => [
          [
              'name' => 'Màu sắc',
              'icon' => 'far fa-circle',
              'route' => 'be.attribute_value.attribute_color.index',
          ],
          [
              'name' => 'Kích cỡ',
              'icon' => 'far fa-circle',
              'route' => 'be.attribute_value.attribute_size.index'
          ]
      ]
    ],
    [
        'name' => 'Quản lý sản phẩm',
        'icon' => 'fas fa-archive',
        'route' => '#',
        'items' => [
            [
                'name' => 'Danh sách sản phẩm',
                'icon' => 'far fa-circle',
                'route' => 'be.product.index'
            ],
            [
                'name' => 'Thêm sản phẩm',
                'icon' => 'far fa-circle',
                'route' => 'be.product.create'
            ],
            [
                'name' => 'Sửa sản phẩm',
                'icon' => 'far fa-circle',
                'route' => '#'
            ]
        ],
    ],
    [
        'name' => 'Mã giảm giá',
        'icon' => 'fas fa-cart-arrow-down',
        'route' => '#',
        'items' => [
            [
                'name' => 'Danh sách mã giảm giá',
                'icon' => 'far fa-circle',
                'route' => 'be.promotion.index'
            ],
            [
                'name' => 'Thêm mã giảm giá',
                'icon' => 'far fa-circle',
                'route' => 'be.promotion.create'
            ],
            [
                'name' => 'Sửa mã giảm giá',
                'icon' => 'far fa-circle',
                'route' => '#'
            ]
        ]
    ],
    [
        'name' => 'Quản lý phí giao hàng',
        'icon' => 'fas fa-shipping-fast',
        'route' => 'be.ship_fee.index',
    ],
    [
        'name' => 'Quản lý đơn hàng',
        'icon' => 'fas fa-shopping-cart',
        'route' => '#',
        'items' => [
            [
                'name' => 'danh sách đơn hàng',
                'icon' => 'far fa-circle',
                'route' => 'be.order.index'
            ],
            [
                'name' => 'Thêm đơn hàng',
                'icon' => 'far fa-circle',
                'route' => 'be.order.create'
            ],
            [
                'name' => 'Sửa đơn hàng',
                'icon' => 'far fa-circle',
                'route' => '#'
            ]
        ]
    ],
];
