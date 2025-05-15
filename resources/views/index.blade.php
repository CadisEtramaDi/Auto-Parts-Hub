@extends('layouts.app')
@section('content')
<main>

    <section class="swiper-container js-swiper-slider swiper-number-pagination slideshow" data-settings='{
        "autoplay": {
          "delay": 5000
        },
        "slidesPerView": 1,
        "effect": "fade",
        "loop": true
      }'>
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <div class="overflow-hidden position-relative h-100">
            <div class="slideshow-character position-absolute bottom-0 pos_right-center">
              <img loading="lazy" src="{{asset('assets/images/home/demo3/slideshow-character1.png')}}" width="542" height="733"
                alt="Woman Fashion 1"
                class="slideshow-character__img animate animate_fade animate_btt animate_delay-9 w-auto h-auto" />
          
            </div>
            <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
              <h6 class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
                New Arrivals</h6>
              <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">Auto Parts</h2>
              <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-5">Hub</h2>
              <a href="#"
                class="btn-link btn-link_lg default-underline fw-medium animate animate_fade animate_btt animate_delay-7">Shop
                Now</a>
            </div>
          </div>
        </div>

        <div class="swiper-slide">
          <div class="overflow-hidden position-relative h-100">
            <div class="slideshow-character position-absolute bottom-0 pos_right-center">
              <img loading="lazy" src="{{asset('assets/images/slideshow-character1.png')}}" width="400" height="733"
                alt="Woman Fashion 1"
                class="slideshow-character__img animate animate_fade animate_btt animate_delay-9 w-auto h-auto" />
              
            </div>
            <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
              <h6 class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
                New Arrivals</h6>
              <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">Auto Parts</h2>
              <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-5">Hub</h2>
              <a href="#"
                class="btn-link btn-link_lg default-underline fw-medium animate animate_fade animate_btt animate_delay-7">Shop
                Now</a>
            </div>
          </div>
        </div>

        <div class="swiper-slide">
          <div class="overflow-hidden position-relative h-100">
            <div class="slideshow-character position-absolute bottom-0 pos_right-center">
              <img loading="lazy" src="{{asset('assets/images/slideshow-character2.png')}}" width="400" height="690"
                alt="Woman Fashion 2"
                class="slideshow-character__img animate animate_fade animate_rtl animate_delay-10 w-auto h-auto" />
            </div>
            <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
              <h6 class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
                New Arrivals</h6>
              <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">Auto Parts</h2>
              <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-5">Hub</h2>
              <a href="#"
                class="btn-link btn-link_lg default-underline fw-medium animate animate_fade animate_btt animate_delay-7">Shop
                Now</a>
            </div>
          </div>
        </div>
      </div>

      <div class="container">
        <div
          class="slideshow-pagination slideshow-number-pagination d-flex align-items-center position-absolute bottom-0 mb-5">
        </div>
      </div>
    </section>
    <div class="container mw-1620 bg-white border-radius-10">
      <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

      <section class="hot-deals container">
        <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">Hot Deals</h2>
        <div class="row">
          <div
            class="col-md-6 col-lg-4 col-xl-20per d-flex align-items-center flex-column justify-content-center py-4 align-items-md-start">
            <h2>Summer Sale</h2>
            <h2 class="fw-bold">Up to 60% Off</h2>

            <div class="position-relative d-flex align-items-center text-center pt-xxl-4 js-countdown mb-3"
              data-date="18-3-2024" data-time="06:50">
              <div class="day countdown-unit">
                <span class="countdown-num d-block"></span>
                <span class="countdown-word text-uppercase text-secondary">Days</span>
              </div>

              <div class="hour countdown-unit">
                <span class="countdown-num d-block"></span>
                <span class="countdown-word text-uppercase text-secondary">Hours</span>
              </div>

              <div class="min countdown-unit">
                <span class="countdown-num d-block"></span>
                <span class="countdown-word text-uppercase text-secondary">Mins</span>
              </div>

              <div class="sec countdown-unit">
                <span class="countdown-num d-block"></span>
                <span class="countdown-word text-uppercase text-secondary">Sec</span>
              </div>
            </div>

            <a href="#" class="btn-link default-underline text-uppercase fw-medium mt-3">View All</a>
          </div>
          <div class="col-md-6 col-lg-8 col-xl-80per">
            <div class="position-relative">
              <div class="swiper-container js-swiper-slider" data-settings='{
                  "autoplay": {
                    "delay": 5000
                  },
                  "slidesPerView": 3,
                  "slidesPerGroup": 3,
                  "effect": "none",
                  "loop": false,
                  "spaceBetween": 15,
                  "breakpoints": {
                    "320": {
                      "slidesPerView": 1,
                      "slidesPerGroup": 1,
                      "spaceBetween": 10
                    },
                    "768": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 2,
                      "spaceBetween": 15
                    },
                    "992": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 2,
                      "spaceBetween": 20,
                      "pagination": false
                    },
                    "1200": {
                      "slidesPerView": 3,
                      "slidesPerGroup": 3,
                      "spaceBetween": 20,
                      "pagination": false
                    }
                  }
                }'>
                <div class="swiper-wrapper">
                  @foreach($carouselProducts as $product)
                  <div class="swiper-slide">
                    <div class="overflow-hidden position-relative h-100" style="min-height: 250px;">
                      <div class="slideshow-character position-absolute top-0 end-0 w-100 h-100 d-flex justify-content-center align-items-center">
                        @php
                          $categorySlug = $product->category ? $product->category->slug : '';
                          $imagePath = 'assets/images/categories/';
                          
                          // Map category slugs to available image files
                          switch($categorySlug) {
                              case 'electrical-components':
                              case 'electrical-system':
                                $imagePath .= 'electrical.jpg';
                                break;
                              case 'brake-system':
                                $imagePath .= 'brake.jpg';
                                break;
                              case 'engine-parts':
                                $imagePath .= 'engine.jpg';
                                break;
                              case 'cooling-components':
                              case 'cooling-system':
                                $imagePath .= 'cooling.jpg';
                                break;
                              case 'transmission-components':
                                $imagePath .= 'transmission.jpg';
                                break;
                              case 'suspension-system':
                                $imagePath .= 'suspension.jpg';
                                break;
                              case 'fuel-system':
                                $imagePath .= 'fuel.jpg';
                                break;
                              case 'exhaust-system':
                                $imagePath .= 'exhaust.jpg';
                                break;
                              default:
                                $imagePath .= 'brake.jpg'; // Default image if no match
                          }
                        @endphp
                        <img loading="lazy" src="{{ asset($imagePath) }}" width="350" height="350"
                          alt="{{ $product->name }}"
                          class="slideshow-character__img animate animate_fade animate_btt animate_delay-9 w-100 h-auto" 
                          style="max-height: 180px; object-fit: contain;" />
                      </div>
                      <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
                        <h6 class="text_dash text-uppercase fs-6 fw-medium animate animate_fade animate_btt animate_delay-3">
                          {{ $product->category ? $product->category->name : 'Product' }}</h6>
                        <h2 class="h5 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">{{ $product->name }}</h2>
                        <h2 class="h5 fw-bold animate animate_fade animate_btt animate_delay-5">${{ $product->sale_price ?: $product->regular_price }}</h2>
                        <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}"
                          class="btn-link btn-link_sm default-underline fw-medium animate animate_fade animate_btt animate_delay-7">Shop
                          Now</a>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

      <section class="category-banner container">
        <div class="row">
          <div class="col-md-6">
            <div class="category-banner__item border-radius-10 mb-5">
              <img loading="lazy" class="h-auto w-100" src="{{asset('assets/images/banners/engine-parts-banner.jpg')}}" width="690" height="665"
                alt="Engine Parts" />
              <div class="category-banner__item-mark">
                Starting at $199
              </div>
              <div class="category-banner__item-content">
                <h3 class="mb-0">Engine Parts</h3>
                <p class="text-white mb-3">High Performance Components</p>
                <a href="#" class="btn btn-outline-light">Shop Engine Parts</a>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="category-banner__item border-radius-10 mb-5">
              <img loading="lazy" class="h-auto w-100" src="{{asset('assets/images/categories/brake.jpg')}}" width="690" height="665"
                alt="Brake System" />
              <div class="category-banner__item-mark">
                Starting at $79
              </div>
              <div class="category-banner__item-content">
                <h3 class="mb-0">Brake System</h3>
                <p class="text-white mb-3">Premium Safety Components</p>
                <a href="#" class="btn btn-outline-light">Shop Brake Parts</a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

      <section class="products-grid container">
        <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">Featured Products</h2>
        <div class="row">
          @foreach($featuredProducts as $product)
          <div class="col-6 col-md-4 col-lg-3">
            <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
              <div class="pc__img-wrapper">
                <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">
                  <img loading="lazy" src="{{ asset('images') }}/{{ $product->image }}" width="330" height="400"
                    alt="{{ $product->name }}" class="pc__img">
                </a>
                @if($product->sale_price)
                <div class="product-label bg-red text-white right-0 top-0 left-auto mt-2 mx-2">Sale</div>
                @endif
              </div>

              <div class="pc__info position-relative">
                <h6 class="pc__title"><a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">{{ $product->name }}</a></h6>
                <div class="product-card__price d-flex align-items-center">
                  @if($product->sale_price)
                  <span class="money price-old">${{ $product->regular_price }}</span>
                  <span class="money price text-secondary">${{ $product->sale_price }}</span>
                  @else
                  <span class="money price text-secondary">${{ $product->regular_price }}</span>
                  @endif
                </div>

                <div class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                  @if(Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
                    <a href="{{ route('cart.index') }}" class="btn-link btn-link_lg me-4 text-uppercase fw-medium">Go to Cart</a>
                  @else
                    <form action="{{ route('cart.add') }}" method="POST" class="d-flex">
                      @csrf
                      <input type="hidden" name="id" value="{{ $product->id }}">
                      <input type="hidden" name="name" value="{{ $product->name }}">
                      <input type="hidden" name="price" value="{{ $product->sale_price ?: $product->regular_price }}">
                      <input type="hidden" name="quantity" value="1">
                      <button type="submit" class="btn-link btn-link_lg me-4 text-uppercase fw-medium">Add to Cart</button>
                    </form>
                  @endif
                  <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                    data-bs-toggle="modal" data-bs-target="#quickView" title="Quick view">
                    <span class="d-none d-xxl-block">Quick View</span>
                    <span class="d-block d-xxl-none">
                      <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <use href="#icon_view" />
                      </svg>
                    </span>
                  </button>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <div class="text-center mt-2">
          <a class="btn-link btn-link_lg default-underline text-uppercase fw-medium" href="{{ route('shop.index') }}">View All Products</a>
        </div>
      </section>
    </div>

    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

    <!-- Featured Products Section -->
    <section class="featured-products container">
      <h2 class="section-title text-center mb-4">Featured Products</h2>
      <div class="row g-4">
        @foreach($featuredProducts as $product)
        <div class="col-6 col-md-4 col-lg-3">
          <div class="product-card h-100 border-radius-10 p-3 bg-white shadow-sm hover-shadow transition-all">
            <div class="position-relative mb-3">
              <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}" class="d-block">
                <img loading="lazy" src="{{ asset('images') }}/{{ $product->image }}" 
                     class="img-fluid rounded product-img" 
                     alt="{{ $product->name }}"
                     style="width: 100%; height: 200px; object-fit: cover;">
              </a>
              @if($product->sale_price)
              <div class="position-absolute top-0 end-0 m-2">
                <span class="badge bg-danger px-2 py-1">Sale</span>
              </div>
              @endif
              <div class="position-absolute bottom-0 start-0 m-2">
                <span class="badge bg-primary px-2 py-1">{{ $product->category->name }}</span>
              </div>
            </div>

            <div class="product-details">
              <h5 class="product-title mb-2">
                <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}" 
                   class="text-dark text-decoration-none">{{ $product->name }}</a>
              </h5>
              
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="product-price">
                @if($product->sale_price)
                  <span class="text-muted text-decoration-line-through me-2">${{ number_format($product->regular_price, 2) }}</span>
                  <span class="text-danger fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                  @else
                  <span class="text-dark fw-bold">${{ number_format($product->regular_price, 2) }}</span>
                  @endif
                </div>
                <div class="stock-status">
                  @if($product->stock_status == 'instock')
                  <span class="badge bg-success">In Stock</span>
                @else
                  <span class="badge bg-danger">Out of Stock</span>
                @endif
                </div>
              </div>

              <div class="product-actions d-flex gap-2">
              @if(Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
                  <a href="{{ route('cart.index') }}" 
                     class="btn btn-primary w-100">
                     <i class="fas fa-shopping-cart me-1"></i> View Cart
                  </a>
              @else
                  <form action="{{ route('cart.add') }}" method="POST" class="w-100">
                  @csrf
                  <input type="hidden" name="id" value="{{ $product->id }}">
                  <input type="hidden" name="name" value="{{ $product->name }}">
                  <input type="hidden" name="price" value="{{ $product->sale_price ?: $product->regular_price }}">
                  <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-primary w-100">
                      <i class="fas fa-cart-plus me-1"></i> Add to Cart
                    </button>
                </form>
              @endif
                <button class="btn btn-outline-primary flex-shrink-0" 
                        data-bs-toggle="modal" 
                        data-bs-target="#quickView" 
                        title="Quick view">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <div class="text-center mt-5">
        <a href="{{ route('shop.index') }}" 
           class="btn btn-outline-primary btn-lg px-4">
          View All Products <i class="fas fa-arrow-right ms-2"></i>
        </a>
      </div>
    </section>

    <!-- New Arrivals Section -->
    <section class="new-arrivals container mt-5 mb-5">
      <h2 class="section-title text-center mb-4">New Arrivals</h2>
      <div class="row g-4">
        @foreach($newArrivals as $product)
        <div class="col-6 col-md-4 col-lg-3">
          <div class="product-card h-100 border-radius-10 p-3 bg-white shadow-sm hover-shadow transition-all">
            <div class="position-relative mb-3">
              <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}" class="d-block">
                <img loading="lazy" src="{{ asset('images') }}/{{ $product->image }}" 
                     class="img-fluid rounded product-img" 
                     alt="{{ $product->name }}"
                     style="width: 100%; height: 200px; object-fit: cover;">
              </a>
              @if($product->sale_price)
              <div class="position-absolute top-0 end-0 m-2">
                <span class="badge bg-danger px-2 py-1">Sale</span>
              </div>
              @endif
              <div class="position-absolute top-0 start-0 m-2">
                <span class="badge bg-info px-2 py-1">New</span>
              </div>
              <div class="position-absolute bottom-0 start-0 m-2">
                <span class="badge bg-primary px-2 py-1">{{ $product->category->name }}</span>
              </div>
            </div>

            <div class="product-details">
              <h5 class="product-title mb-2">
                <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}" 
                   class="text-dark text-decoration-none">{{ $product->name }}</a>
              </h5>
              
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="product-price">
                @if($product->sale_price)
                  <span class="text-muted text-decoration-line-through me-2">${{ number_format($product->regular_price, 2) }}</span>
                  <span class="text-danger fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                  @else
                  <span class="text-dark fw-bold">${{ number_format($product->regular_price, 2) }}</span>
                  @endif
                </div>
                <div class="stock-status">
                  @if($product->stock_status == 'instock')
                  <span class="badge bg-success">In Stock</span>
                @else
                  <span class="badge bg-danger">Out of Stock</span>
                @endif
                </div>
              </div>

              <div class="product-actions d-flex gap-2">
              @if(Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
                  <a href="{{ route('cart.index') }}" 
                     class="btn btn-primary w-100">
                     <i class="fas fa-shopping-cart me-1"></i> View Cart
                  </a>
              @else
                  <form action="{{ route('cart.add') }}" method="POST" class="w-100">
                  @csrf
                  <input type="hidden" name="id" value="{{ $product->id }}">
                  <input type="hidden" name="name" value="{{ $product->name }}">
                  <input type="hidden" name="price" value="{{ $product->sale_price ?: $product->regular_price }}">
                  <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-primary w-100">
                      <i class="fas fa-cart-plus me-1"></i> Add to Cart
                    </button>
                </form>
              @endif
                <button class="btn btn-outline-primary flex-shrink-0" 
                        data-bs-toggle="modal" 
                        data-bs-target="#quickView" 
                        title="Quick view">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <div class="text-center mt-5">
        <a href="{{ route('shop.index') }}" 
           class="btn btn-outline-primary btn-lg px-4">
          Browse All New Arrivals <i class="fas fa-arrow-right ms-2"></i>
        </a>
      </div>
    </section>

  </main>
@endsection~