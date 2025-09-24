<div class="container py-5">
    <h2 class="mb-4">Checkout</h2>

    @if(count($cartItems) > 0)
        <div class="row">
            <!-- Shipping / Billing Form -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Shipping Information</h5>

                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" class="form-control" wire:model.defer="name">
                            @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" wire:model.defer="email">
                            @error('email') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" class="form-control" wire:model.defer="phone">
                            @error('phone') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label>Address</label>
                            <textarea class="form-control" wire:model.defer="address"></textarea>
                            @error('address') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label>City</label>
                                <input type="text" class="form-control" wire:model.defer="city">
                                @error('city') <span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-6 mb-3">
                                <label>State</label>
                                <input type="text" class="form-control" wire:model.defer="state">
                                @error('state') <span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>ZIP Code</label>
                            <input type="text" class="form-control" wire:model.defer="zip">
                            @error('zip') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <!-- Stripe Card Input -->
                        <div class="mb-4">
                            <label class="form-label">Card Details</label>
                            <div id="card-element" class="form-control" wire:ignore></div>
                            <div id="card-errors" class="text-danger mt-2"></div>
                        </div>
                        
                        <button id="card-button"
                            class="btn btn-primary w-100"
                            data-secret="{{ $paymentIntentSecret }}">
                            Pay ₹{{ number_format($grandTotal, 2) }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <hr>
                        @foreach($cartItems as $item)
                            @php
                                $isArray = is_array($item);
                                $name = $isArray ? $item['name'] : ($item->product->name ?? '-');
                                $quantity = $isArray ? $item['quantity'] : $item->quantity;
                                $price = $isArray ? $item['price'] : ($item->product->price ?? 0);
                                $subtotal = $price * $quantity;
                            @endphp
                            <p class="d-flex justify-content-between mb-1">
                                <span>{{ $name }} x {{ $quantity }}</span>
                                <span>₹{{ number_format($subtotal, 2) }}</span>
                            </p>
                        @endforeach
                        <hr>
                        <h5 class="d-flex justify-content-between">
                            <span>Total</span>
                            <span>₹{{ number_format($grandTotal, 2) }}</span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <h4>No items found in your cart for checkout.</h4>
            <p class="text-muted">Please add products to your cart before proceeding to checkout.</p>
            <a href="{{ route('home') }}" class="btn btn-outline-primary mt-3">Continue Shopping</a>
        </div>    
    @endif
</div>