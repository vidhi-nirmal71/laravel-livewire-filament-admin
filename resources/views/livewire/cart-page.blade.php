<div class="container py-5">
    <h2 class="mb-4">Your Cart</h2>

    @if(count($cartItems) > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-12 col-lg-8">
                @php $grandTotal = 0; @endphp
                @foreach($cartItems as $item)
                    @php
                        $isArray = is_array($item);
                        $productId = $isArray ? $item['product_id'] : $item->product_id;
                        $name = $isArray ? $item['name'] : ($item->product->name ?? '-');
                        $quantity = $isArray ? $item['quantity'] : $item->quantity;
                        $price = $isArray ? $item['price'] : ($item->product->price ?? 0);
                        $image = $isArray ? $item['image'] : ($item->product->image_url ?? null);
                        $subtotal = $price * $quantity;
                        $grandTotal += $subtotal;
                    @endphp

                    <div class="card mb-3 shadow-sm">
                        <div class="row g-2 g-md-0 align-items-center p-2">
                            <div class="col-4 col-md-2 text-center">
                                @if($image)
                                    <img src="{{ $image }}" alt="{{ $name }}" class="img-fluid" style="max-height: 80px;">
                                @else
                                    <div class="bg-light text-center py-3">No Image</div>
                                @endif
                            </div>

                            <div class="col-8 col-md-4">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1">{{ $name }}</h6>
                                    <p class="card-text text-muted mb-0">₹{{ number_format($price, 2) }}</p>
                                </div>
                            </div>

                            <div class="col-6 col-md-3 mt-2 mt-md-0">
                                <div class="d-flex align-items-center">
                                    <button wire:click="decreaseQuantity({{ $productId }})" class="btn btn-outline-secondary btn-sm">-</button>
                                    <input type="text" value="{{ $quantity }}" class="form-control text-center mx-1" style="max-width: 50px;" readonly>
                                    <button wire:click="increaseQuantity({{ $productId }})" class="btn btn-outline-secondary btn-sm">+</button>
                                </div>
                            </div>

                            <div class="col-6 col-md-2 text-center mt-2 mt-md-0">
                                <p class="mb-0 fw-bold">₹{{ number_format($subtotal, 2) }}</p>
                            </div>

                            <div class="col-12 col-md-1 text-center mt-2 mt-md-0">
                                <button wire:click="removeItem({{ $productId }})" class="btn btn-sm btn-danger">&times;</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-12 col-lg-4 mt-4 mt-lg-0">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <hr>
                        <p class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($grandTotal, 2) }}</span>
                        </p>
                        <p class="d-flex justify-content-between">
                            <span>Shipping</span>
                            <span>Free</span>
                        </p>
                        <hr>
                        <h5 class="d-flex justify-content-between">
                            <span>Total</span>
                            <span>₹{{ number_format($grandTotal, 2) }}</span>
                        </h5>
                        <a href="{{ route('checkout') }}" class="btn btn-primary w-100 mt-3">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <h4>Your cart is empty.</h4>
            <a href="{{ route('home') }}" class="btn btn-outline-primary mt-3">Shop Now</a>
        </div>
    @endif
</div>