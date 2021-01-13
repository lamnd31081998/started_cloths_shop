<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta property="og:image" content="{{ asset(\Illuminate\Support\Facades\DB::table('generals')->where('type', '=', 'contact')->first()->images) }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@yield('title')" />
    <meta property="og:description" content="aStar - Mua sắm không thể dễ dàng hơn" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset(DB::table('generals')->where('type', '=', 'contact')->first()->small_images) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/styles/bootstrap-4.1.3/bootstrap.css') }}">
    <link href="{{ asset('aStar/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/plugins/OwlCarousel2-2.2.1/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/plugins/OwlCarousel2-2.2.1/owl.theme.default.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('aStar/plugins/OwlCarousel2-2.2.1/animate.css') }}">
    <!-- Select2 -->
    <link href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('select2-bootstrap/select2-bootstrap.css') }}"/>
    @yield('css')
    <style>
        .preview_product_image {
            height:456.88px;
            width: 100%;
        }

        .table {
            color: black;
        }

        @media only screen and (min-width: 768px) {
            .footer_social_title {
                display: block !important;
            }
        }

        @media (max-width: 991.98px) {
            .parallax-window {
                min-height: unset;
            }
            .footer_content {
                padding-top: 20px;
                padding-bottom: 0;
            }

            .footer_about_text {
                margin-top: 15px;
            }

            .footer_list, .footer_contact_list, .footer_blog_container {
                margin-top: 15px;
            }

            .footer_social_container {
                height: 50px;
            }

            .sidebar_nav, .cart {
                margin-top: 30px;
            }
            .col-xxl-3.col-md-6.footer_col {
                margin-bottom: 20px;
            }
        }

        #breadcrumbs {
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        .form-control {
            color: black;
        }

        #breadcrumbs li, #breadcrumbs li a {
            color: black;
        }

        #cart_text {
            font-weight: normal;
        }

        .table-responsive > .table-bordered {
            border: 1px solid #dee2e6
        }
    </style>
</head>
<body onload="getlinks()">

<div class="super_container">
    <div id="header_box">
        @include('frontend.layout.header')
    </div>

    @yield('home')

    @yield('boxes')

    @yield('categories')

    @yield('carts')

    @yield('checkout')

    @yield('products')

    @yield('optional')

    @include('frontend.layout.footer')

</div>

<script src="{{ asset('aStar/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('aStar/styles/bootstrap-4.1.3/popper.js') }}"></script>
<script src="{{ asset('aStar/styles/bootstrap-4.1.3/bootstrap.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/greensock/TweenMax.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/greensock/TimelineMax.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/scrollmagic/ScrollMagic.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/greensock/animation.gsap.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/greensock/ScrollToPlugin.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>
<script src="{{ asset('aStar/plugins/easing/easing.js') }}"></script>
<script src="{{ asset('aStar/plugins/parallax-js-master/parallax.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/Isotope/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('aStar/plugins/Isotope/fitcolumns.js') }}"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@yield('js')
{!! $contact->fb_script !!}

</body>
</html>
