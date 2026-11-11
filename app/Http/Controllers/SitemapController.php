<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory; 
use App\Models\CatalogCollection; 

class SitemapController extends Controller
{
    public function index()
    {
        // 1. MAIN STATIC PAGES (Sirf woh jo waqai exist karte hain)
        $urls = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            
            // Yahan apne About/Contact/Policy pages ke asli URLs dalein
            ['loc' => url('/about-us'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => url('/contact'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => url('/faqs'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => url('/privacy'), 'priority' => '0.4', 'changefreq' => 'yearly'],
            ['loc' => url('/terms'), 'priority' => '0.4', 'changefreq' => 'yearly'],
            ['loc' => url('/shipping'), 'priority' => '0.4', 'changefreq' => 'yearly'],
            ['loc' => url('/returns'), 'priority' => '0.4', 'changefreq' => 'yearly'],
        ];

        // 2. ACTIVE COLLECTIONS (Sirf tab aayenge jab database mein hon)
        // $collections = CatalogCollection::where('is_active', 1)->get(); 
        // foreach ($collections as $collection) {
        //     $urls[] = [
        //         'loc' => url('/collections/' . $collection->slug),
        //         'priority' => '0.8',
        //         'changefreq' => 'weekly',
        //     ];
        // }

        // 3. ACTIVE CATEGORIES (Sirf tab aayengi jab database mein hon)
        // $categories = ProductCategory::where('is_active', 1)->get(); 
        // foreach ($categories as $category) {
        //     $urls[] = [
        //         'loc' => url('/category/' . $category->slug), 
        //         'priority' => '0.8',
        //         'changefreq' => 'weekly',
        //     ];
        // }

        // 4. ACTIVE PRODUCTS (Yeh aapki site par mojood hain)
        $products = Product::where('is_active', 1)->get(); 
        
        foreach ($products as $product) {
            $urls[] = [
                'loc' => url('/product/' . $product->slug),
                'priority' => '0.9',
                'changefreq' => 'weekly',
            ];
        }

        return response()->view('sitemap', compact('urls'))
            ->header('Content-Type', 'text/xml');
    }
}