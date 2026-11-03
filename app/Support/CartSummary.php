<?php

namespace App\Support;

use App\Models\Setting;

class CartSummary
{
    public static function for(array $cart): array
    {
        $subtotal = (float) collect($cart)->sum(
            fn ($item) => (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0)
        );
        $freeShippingThreshold = max(0, (float) Setting::get('free_shipping_threshold', 10000));
        $standardShippingFee = max(0, (float) Setting::get('shipping_fee', 250));
        $shipping = $subtotal > 0 && $subtotal < $freeShippingThreshold
            ? $standardShippingFee
            : 0;

        return [
            'item_count' => (int) collect($cart)->sum(fn ($item) => (int) ($item['quantity'] ?? 0)),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
            'free_shipping_threshold' => $freeShippingThreshold,
            'amount_until_free_shipping' => max(0, $freeShippingThreshold - $subtotal),
            'shipping_progress' => $freeShippingThreshold > 0
                ? min(100, (int) round($subtotal / $freeShippingThreshold * 100))
                : 100,
        ];
    }
}
