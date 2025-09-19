<div class="product-container">
    <h2>Our Products</h2>

    <div class="product-grid">
        @forelse ($products as $product)
            <div class="product-card">
                <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->name }}">
                <div class="product-info">
                    <h3>{{ $product->name }}</h3>
                    <p class="desc">{{ $product->description }}</p>
                    <p class="price">₹{{ number_format($product->price, 2) }}</p>
                    <p class="stock {{ $product->stock > 0 ? 'in-stock' : 'out-stock' }}">
                        {{ $product->stock > 0 ? $product->stock . ' available' : 'Out of stock' }}
                    </p>
                    <button class="btn" @if($product->stock <= 0) disabled @endif>
                        Add to Cart
                    </button>
                </div>
            </div>
        @empty
            <p class="no-products">No products available.</p>
        @endforelse
    </div>
</div>
