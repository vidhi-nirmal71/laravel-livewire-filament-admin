<div class="container py-5">
    <h2 class="text-center mb-4">Our Products</h2>

    @if (session()->has('success'))
        <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="alert alert-success">
            {{ session('success') }}
        </p>
    @endif

    @if (session()->has('error'))
        <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="alert alert-danger">
            {{ session('error') }}
        </p>
    @endif


    <div class="row g-4">
        @forelse ($products as $product)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 350px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-truncate">{{ $product->name }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($product->description, 60) }}</p>
                        <p class="fw-bold mb-1">₹{{ number_format($product->price, 2) }}</p>
                        <p class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }} small">
                            {{ $product->stock > 0 ? $product->stock . ' available' : 'Out of stock' }}
                        </p>
                        <button class="btn btn-primary mt-auto"
                                wire:click="addToCart({{ $product->id }})"
                                @if($product->stock <= 0) disabled @endif>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">No products available.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
