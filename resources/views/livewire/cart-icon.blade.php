<a href="{{ route('cart.index') }}" class="cart-icon">
    <svg fill="none" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 3h2l.4 2M7 13h10l4-8H5.4
               M7 13l-1 5h13l-1-5
               M7 13H5.4
               M17 13l1 5
               M6 18a1 1 0 1 1-2 0 1 1 0 0 1 2 0z
               M20 18a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
    </svg>

    <span class="cart-badge">{{ $count }}</span>
</a>