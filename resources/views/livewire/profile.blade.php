@extends('layout.master', ['title' => 'My Profile'])

@section('content')
<div class="container py-3">    
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">User Details</h5>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Phone:</strong> {{ $user->phone ?? '-' }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">Order History</h5>
            @if($orders->count() > 0)
                <div class="list-group">
                    @foreach($orders as $order)
                        <a href="{{ route('profile.order.view', $order->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Order #{{ $order->id }} | ₹{{ number_format($order->total_amount, 2) }}</span>
                            <span>{{ $order->created_at->format('d M, Y') }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-muted">You have no orders yet.</p>
            @endif
        </div>
    </div>
    <a href="{{ route('home') }}" class="btn btn-outline-secondary mt-3">Shop More</a>
</div>
@endsection