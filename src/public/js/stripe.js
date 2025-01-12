var stripeKey = document.getElementById('stripe-container').getAttribute('data-stripe-key');
var stripe = Stripe(stripeKey);

var form = document.getElementById('purchase-form')

function showError(message) {
    const errorDiv = document.querySelector('.stripe-error-message');
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    } else {
        console.error('Error div not found:', message);
    }
}

document.getElementById('payment-method').addEventListener('change', function() {
    document.querySelector('.payment-method').textContent = this.options[this.selectedIndex].text;
});

form.addEventListener('submit', function(e) {
    e.preventDefault();

    if (!navigator.onLine) {
        alert('インターネット接続がありません。接続を確認してください。');
        return;
    }

    fetch('/create-checkout-session', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: form.product_id.value,
            shipping_address_id: form.shipping_address_id.value,
            payment_method: form.payment_method.value,
        }),
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                if (response.status === 422 && data.errors) {
                    for (let key in data.errors) {
                        showError(data.errors[key][0]);
                        break;
                    }
                } else if (data.message) {
                    showError(data.message);
                } else {
                    showError('サーバーエラーが発生しました。再度お試しください。');
                }
                throw new Error('サーバーエラー');
            });
        }
        return response.json();
    })
    .then(data => {
        return stripe.redirectToCheckout({ sessionId: data.id });
    })
    .then(result => {
        if (result.error) {
            console.error(result.error.message);
            showError('決済ページへの遷移に失敗しました。再度お試しください。');
        }
    })
    .catch(error => {
        if (error.message !== 'サーバーエラー') {
            console.error('ネットワークエラー:', error);
            showError('ネットワークエラーが発生しました。再度お試しください。');
        }
    });
});
