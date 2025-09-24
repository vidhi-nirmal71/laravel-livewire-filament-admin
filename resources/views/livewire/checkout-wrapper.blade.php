@extends('layout.master', ['title' => "Checkout"])

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <livewire:checkout-page />
</div>
@endsection

@section('script')
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const stripe = Stripe("{{ config('services.stripe.key') }}");
        const elements = stripe.elements();
        const card = elements.create('card');
        const cardElementDiv = document.getElementById('card-element');
        card.mount('#card-element');

        const cardButton = document.getElementById('card-button');
        cardButton.addEventListener('click', async (e) => {
            e.preventDefault();
            cardButton.disabled = true;

            const clientSecret = cardButton.dataset.secret;
            const {paymentIntent, error} = await stripe.confirmCardPayment(
                clientSecret,
                { payment_method: { card } }
            );

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                cardButton.disabled = false;
            } else if (paymentIntent.status === 'succeeded') {
                Livewire.dispatch('placeOrder');
            }
        });

        // Optional: Listen to Livewire event to refresh card element if needed
        // Livewire.on('refreshStripe', () => {
        //     card.unmount();
        //     card.mount('#card-element');
        // });
    });
</script>

@endsection