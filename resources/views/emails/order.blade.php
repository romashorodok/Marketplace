@component('emails.components.message')
    @slot('styles')
        <style>
            img {
                width: 80px;
                height: 80px;
            }

            th {
                color: #a9a9a9;
            }

            .product-list {
                margin: auto;
            }

            .product-list tbody {
                border-spacing: 20px;
                border-collapse: initial;
            }

            .email-separator {
                display: block;
                border: 2px solid #fceae8;
                width: 100%;
            }

            .total-price {
                text-align: right;
                font-weight: bold;
                font-size: 18px;
            }

            .order-number {
                text-align: center;
                font-size: 18px;
                font-weight: bold;
            }

            .order-message {
                white-space: pre;
                margin-bottom: 20px;
                margin-top: 0;
            }
        </style>
    @endslot

    @slot('header')
        Thank you for your order!
    @endslot

    <p class="order-message">
        You recently ordered items.

        The delivery service will ship order as soon as possible!
    </p>

    <p class="order-number">
        Your order № {{ $order->charge_token }}
    </p>

    <span class="email-separator"></span>

    <table class="product-list">
        <tr>
            <th>Name</th>
            <th>Quantity</th>
            <th>Per one</th>
            <th>Total</th>
        </tr>
        @foreach($order->billingItems as $item)
            @php($product = $item->product)
            <tr>
                <td>
                    {{ $product->name }}
                </td>
                <td>
                    {{ $item->quantity }}
                </td>
                <td>
                    {{ $item->price }} ₴
                </td>
                <td>
                    {{ $item->quantity_price }} ₴
                </td>
            </tr>
        @endforeach
    </table>

    <span class="email-separator"></span>

    <p class="total-price">
        Total price: {{ $order->total_price }} ₴
    </p>
@endcomponent
