<?php

namespace App\Http\Controllers;

use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
        $ids = array_map('intval', session('wishlist', []));
        $products = Product::where('is_active', true)->with('productCategory')->whereIn('id', $ids)->get()
            ->sortBy(fn (Product $product) => array_search($product->id, $ids, true))
            ->values();

        return view('frontend.wishlist.index', compact('products'));
    }

    public function toggle(int $productId)
    {
        $product = Product::findOrFail($productId);
        abort_unless($product->is_active, 404);

        $wishlist = array_map('intval', session('wishlist', []));
        $index = array_search($product->id, $wishlist, true);

        if ($index === false) {
            $wishlist[] = $product->id;
            $added = true;
        } else {
            unset($wishlist[$index]);
            $wishlist = array_values($wishlist);
            $added = false;
        }

        session(['wishlist' => $wishlist]);

        return response()->json([
            'added' => $added,
            'count' => count($wishlist),
            'message' => $added ? 'Added to your wishlist.' : 'Removed from your wishlist.',
        ]);
    }
}
