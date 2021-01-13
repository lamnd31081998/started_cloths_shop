<!-- Footer -->

<footer class="footer">
    <div class="footer_content">
        <div class="section_container">
            <div class="container">
                <div class="row">

                    <!-- About -->
                    <div class="col-xxl-3 col-md-6 footer_col">
                        <div class="footer_about">
                            <!-- Logo -->
                            <div class="footer_logo">
                                <a href="{{ route('be.index') }}">
                                    @if ($contact->images == "")
                                        <div>a<span>star</span></div>
                                    @else
                                        <img class="img-fluid" src="{{ asset($contact->images) }}">
                                    @endif
                                </a>
                            </div>
                            <div class="footer_about_text">
                                <p>{!! $contact->description !!}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Questions -->
                    <div class="col-xxl-3 col-md-6 footer_col">
                        <div class="footer_questions">
                            <div class="footer_title">Đường dẫn nhanh</div>
                            <div class="footer_list">
                                <ul>
                                    @foreach($fast_links as $link)
                                        <li><a href="{{ $link->link }}">{{ $link->description }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="col-xxl-3 col-md-6 footer_col">
                        <div class="footer_contact">
                            <div class="footer_title">Thông tin cửa hàng</div>
                            <div class="footer_contact_list">
                                <ul>
                                    <li class="d-flex flex-row align-items-start justify-content-start"><span>C.</span><div>{{ $contact->name }}</div></li>
                                    <li class="d-flex flex-row align-items-start justify-content-start"><span>A.</span><div>{{ $contact->address }}, {{ \App\Models\Xa::getXaById($contact->xa_id)->name }}, {{ \App\Models\Huyen::getHuyenById($contact->huyen_id)->name }}, {{ \App\Models\Tinh::getTinhById($contact->tinh_id)->name }}</div></li>
                                    <li class="d-flex flex-row align-items-start justify-content-start"><span>T.</span><div>{{ $contact->phone_number }}</div></li>
                                    <li class="d-flex flex-row align-items-start justify-content-start"><span>E.</span><div>{{ $contact->email }}</div></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Blog -->
                    <div class="col-xxl-3 col-md-6 footer_col">
                        <div class="footer_blog">
                            <div class="footer_title">Bản đồ đường đi</div>
                            <div class="footer_blog_container">


                                <!-- Blog Item -->
                                <div class="footer_blog_item d-flex flex-row align-items-start justify-content-start">
                                    {!! $contact->link !!}
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Social -->
    <div class="footer_social">
        <div class="section_container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="footer_social_container d-flex flex-row align-items-center justify-content-between">
                            @foreach($social_links as $social_link)
                                <a href="{{ $social_link->link == "#" ? 'javascript:void(0)' : 'https://'.$social_link->link }}" target="{{ $social_link->link == "#" ? '' : '_blank' }}">
                                    <div class="footer_social_item d-flex flex-row align-items-center justify-content-start">
                                        <div class="footer_social_icon"><i class="fa fa-{{ \Illuminate\Support\Str::slug($social_link->name, '') }}" aria-hidden="true"></i></div>
                                        <div class="footer_social_title">{{ $social_link->name }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
