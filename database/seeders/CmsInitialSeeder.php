<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class CmsInitialSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Initial Site & CMS Settings
        $defaults = [
            // Announcement Bar
            'announcement_enabled' => '1',
            'announcement_text' => 'Free shipping on orders above Rs. 10,000',
            'free_shipping_threshold' => '10000',

            // Brand & Footer
            'site_name' => 'G. Collection',
            'site_tagline' => 'Wear the Story, Every Stitch',
            'footer_about' => "A full collection of stitched and unstitched ladies' clothing from Karachi, Pakistan.",
            'contact_address' => 'Karachi, Pakistan',
            'contact_hours' => 'Mon–Sat, 11:00 AM – 9:00 PM',
            'contact_phone' => '+92 334 7779231',
            'whatsapp_number' => '923347779231',
            'contact_email' => 'support@gdotcollection.com',
            'social_instagram' => 'https://instagram.com',
            'social_facebook' => '',
            'social_tiktok' => '',
            'copyright_text' => 'G. Collection. All rights reserved.',

            // Home - Collections section
            'collections_title' => "Three ways\nto dress the day.",
            'collections_subtitle' => 'Stitched, unstitched and everything that goes with it. Pick a collection to start.',
            'collection_1_title' => 'Stitched',
            'collection_1_slug' => 'stitched',
            'collection_1_image' => '',
            'collection_2_title' => 'Unstitched',
            'collection_2_slug' => 'unstitched',
            'collection_2_image' => '',
            'collection_3_title' => 'Accessories',
            'collection_3_slug' => 'accessories',
            'collection_3_image' => '',

            // Home - Most Loved
            'most_loved_title' => "Most loved,\nright now.",
            'most_loved_link_text' => 'See all',
            'most_loved_link_url' => '/shop',

            // Home - Marquee
            'marquee_items' => "Embroidery · Lace · Lawn · Cutwork",

            // Home - Plum Feature Section
            'plum_watermark' => 'Lace. Fringe.',
            'plum_b1_title' => "Lace.<br><span class=\"italic text-rose\">Fringe.</span><br>Cutwork.",
            'plum_b1_desc' => 'Three details, one silhouette. Each piece is finished by hand in our Karachi workshop.',
            'plum_b1_btn_text' => 'Add to bag',
            'plum_b1_btn_url' => '/shop',
            'plum_b1_image' => '',
            'plum_b2_title' => "Lace.<br><span class=\"italic text-rose\">Fringe.</span><br>Cutwork.",
            'plum_b2_desc' => 'These details make these pieces fit the collection, wear after wear.',
            'plum_b2_btn_text' => 'Shop the edit',
            'plum_b2_btn_url' => '/shop',
            'plum_b2_image_1' => '',
            'plum_b2_image_2' => '',

            // Home - The Edit Section
            'edit_badge_1' => 'The Edit',
            'edit_badge_2' => 'Embroidery · Lace · Lawn',
            'edit_badge_3' => 'Karachi',
            'edit_title' => "Made<br><span class=\"italic\">by hand,</span><br>worn by<br>you.",
            'edit_desc' => 'Soft lace sleeves, fringed hems, scalloped cutwork borders. The details that make a hand-finished kurta.',
            'edit_btn_text' => 'Shop the edit',
            'edit_btn_url' => '/shop',

            // Home - Find Your Piece
            'find_piece_title' => 'Find your piece.',

            // Home - Good to Know
            'good_to_know_badge' => 'Good to know',
            'good_to_know_title' => "Shopping with<br>G. Collection",
            'good_to_know_desc' => "A full collection of stitched and unstitched ladies' clothing, based in Karachi, Pakistan. Our team is available Mon–Sat 11:00 AM – 9:00 PM.",
            'good_to_know_1_title' => 'Free shipping',
            'good_to_know_1_desc' => 'On bills of Rs. 10,000 and above',
            'good_to_know_2_title' => 'Pay your way',
            'good_to_know_2_desc' => 'Cash on Delivery · Bank Transfer',
            'good_to_know_3_title' => 'Talk to us',
            'good_to_know_3_desc' => 'Mon–Sat 11:00 AM – 9:00 PM',
            'good_to_know_4_title' => 'Based in',
            'good_to_know_4_desc' => 'Karachi, Pakistan',

            // Home - FAQ
            'faq_section_title' => 'Questions, answered.',
            'faq_section_subtitle' => "Can't find what you need? Talk to us on WhatsApp.",
            'faq_timing' => 'Mon–Sat, 11:00 AM – 9:00 PM',

            // Home - Newsletter
            'newsletter_title' => "Be first<br>to see<br><span class=\"italic text-plum\">what's new.</span>",
            'newsletter_desc' => 'New arrivals and news from G. Collection, in your inbox. Just your email, nothing else.',
            'newsletter_monogram' => 'G.',
        ];

        foreach ($defaults as $key => $val) {
            Setting::firstOrCreate(['key' => $key], ['value' => $val]);
        }

        // 2. Initial FAQs
        if (Faq::count() === 0) {
            $faqs = [
                ['Ordering & payments', 'How do I pay?', 'Choose Cash on Delivery or Bank Transfer at checkout. Bank details appear on the confirmation page.', 1],
                ['Ordering & payments', 'How does ordering work?', 'Browse the collection, add items to your cart, and proceed to checkout.', 2],
                ['Ordering & payments', 'Can I change or cancel an order?', 'Contact us on WhatsApp as soon as possible and we will help before it ships.', 3],
                ['Shipping & delivery', 'How much is shipping?', 'Free shipping on orders above Rs. 10,000. Below that, standard delivery charges apply.', 4],
                ['Shipping & delivery', 'How long will delivery take?', 'Orders usually arrive within a few working days depending on your city.', 5],
                ['Shipping & delivery', 'Where do you deliver?', 'We deliver across Pakistan from our Karachi base.', 6],
                ['Sizes & products', 'What\'s the difference between stitched and unstitched?', 'Stitched is ready to wear. Unstitched is fabric you take to your own tailor.', 7],
                ['Sizes & products', 'Which sizes and colours are available?', 'Each product page lists the sizes and colours currently in stock.', 8],
                ['Sizes & products', 'What kind of pieces do you sell?', 'Embroidered, lace and lawn ladies\' clothing, stitched and unstitched, plus accessories.', 9],
                ['Returns & exchanges', 'Can I exchange an item?', 'Please contact us via WhatsApp within 48 hours of delivery to discuss exchanges.', 10],
                ['Returns & exchanges', 'How long do I have to return something?', 'Within 48 hours of delivery. Message us on WhatsApp to start.', 11],
                ['Customer support', 'When is the team available?', 'Mon–Sat, 11:00 AM – 9:00 PM.', 12],
                ['Customer support', 'How do I contact your team?', 'WhatsApp +92 334 7779231 or support@gdotcollection.com.', 13],
                ['Customer support', 'What happens to my details?', 'We only use them to process and deliver your order.', 14],
            ];

            foreach ($faqs as [$cat, $q, $a, $sort]) {
                Faq::create([
                    'category' => $cat,
                    'question' => $q,
                    'answer' => $a,
                    'sort_order' => $sort,
                    'is_active' => true,
                ]);
            }
        }
    }
}
