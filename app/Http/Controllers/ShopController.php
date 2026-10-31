<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class ShopController extends Controller
{
    // public function home()
    // {
    //     $products = Product::where('is_active', true)->latest()->take(8)->get();
    //     return view('shop.home', compact('products'));
    // }

    public function index()
    {
        $products = Product::where('is_active', true)->latest()->paginate(12);
        return view('shop.index', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $related = Product::where('is_active', true)->where('id', '!=', $product->id)->take(4)->get();
        return view('shop.show', compact('product', 'related'));
    }


    public function home()
    {
        $sliders = \App\Models\HomeSlider::active()->get();
        $products = Product::where('is_active', true)->latest()->take(8)->get();
        return view('shop.home', compact('products', 'sliders'));
    }

    public function about()
    {
        return view('shop.about');
    }
    // public function blogs()
    // {
    //     return view('shop.blogs');
    // }
    public function contact()
    {
        return view('shop.contact');
    }


    public function send(Request $request)
    {
        // Honeypot: bots fill this hidden field, real people never see it.
        if ($request->filled('website')) {
            return redirect()->route('contact')->with('contact_sent', true);
        }

        $data = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:150'],
            'phone'   => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:60'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $body = "New contact message\n\n"
            . "Name: {$data['name']}\n"
            . "Email: {$data['email']}\n"
            . "Phone: " . ($data['phone'] ?? '-') . "\n"
            . "Subject: {$data['subject']}\n\n"
            . $data['message'];

        // Always keep a copy in the log so nothing is lost if mail isn't configured yet.
        Log::info('Contact form', $data);

        try {
            Mail::raw($body, function ($m) use ($data) {
                $m->to('support@gdotcollection.com')
                  ->replyTo($data['email'], $data['name'])
                  ->subject('[G. Collection] ' . $data['subject'] . ' — ' . $data['name']);
            });
        } catch (\Throwable $e) {
            Log::warning('Contact mail failed: ' . $e->getMessage());
        }

        return redirect()->route('contact')->with('contact_sent', true);
    }

    // 
    private function posts(): Collection
    {
        return collect([
            [
                'slug' => 'how-to-care-for-embroidered-lawn',
                'title' => 'How to care for embroidered lawn so it lasts seasons',
                'category' => 'Care',
                'date' => '2026-10-18',
                'read' => 4,
                'tone' => ['F8DCE8', '5B1A4A'],
                'excerpt' => 'A few small habits in the wash and the wardrobe keep threadwork crisp and colours true.',
                'body' => [
                    ['p', 'Embroidery is the first thing people notice and the first thing that suffers from rough handling. The good news: a few small habits go a long way.'],
                    ['h2', 'Wash gently, and inside out'],
                    ['p', 'Turn the piece inside out before washing so the threadwork is not rubbing against anything. Use cool water and a mild detergent, and skip the harsh wringing. Press the water out with a towel instead.'],
                    ['h2', 'Dry in the shade'],
                    ['p', 'Direct sun fades colour quickly, especially deeper shades like magenta and black. Hang the piece in the shade, away from heat, and let it dry fully before folding.'],
                    ['quote', 'Treat embroidery like jewellery for your clothes: gentle, shaded and never rushed.'],
                    ['h2', 'Iron on the reverse'],
                    ['p', 'Place a soft cloth over the embroidered area and iron from the back on a low setting. This keeps the stitches raised and avoids shine on the thread.'],
                ],
            ],
            [
                'slug' => 'stitched-vs-unstitched-which-is-for-you',
                'title' => 'Stitched or unstitched? How to choose what suits you',
                'category' => 'Guide',
                'date' => '2026-10-10',
                'read' => 5,
                'tone' => ['E9E0FB', '5F32A8'],
                'excerpt' => 'Ready to wear today, or fabric shaped exactly to you. A simple way to decide.',
                'body' => [
                    ['p', 'Both have their charm, and plenty of wardrobes have both. Here is a quick way to decide which one fits the moment.'],
                    ['h2', 'Choose stitched when'],
                    ['p', 'You want to wear it this week, you are buying for an event coming up fast, or you simply like the ease of opening a box and getting dressed. Stitched pieces are made to be worn right away.'],
                    ['h2', 'Choose unstitched when'],
                    ['p', 'You have a tailor you trust, a particular silhouette in mind, or you like adjusting sleeve length, neckline and fit to the way you live. Unstitched gives you the fabric and leaves the shape to you.'],
                    ['quote', 'Fabric in your hands, shape in your mind.'],
                    ['p', 'Not sure? Message us on WhatsApp with the piece you like and we will tell you how it wears.'],
                ],
            ],
            [
                'slug' => 'lace-fringe-cutwork-explained',
                'title' => 'Lace, fringe and cutwork: the details that finish a kurta',
                'category' => 'Craft',
                'date' => '2026-09-28',
                'read' => 3,
                'tone' => ['F5C8DA', '5B1A4A'],
                'excerpt' => 'Three small details that quietly turn a simple kurta into something special.',
                'body' => [
                    ['p', 'Look closely at a piece you love and you will usually find one of three details doing the heavy lifting.'],
                    ['h2', 'Lace'],
                    ['p', 'Soft borders on sleeves and hems add movement and a feminine edge without feeling heavy.'],
                    ['h2', 'Fringe'],
                    ['p', 'Tiny tassels catch the light as you move. A little fringe goes a long way and instantly feels festive.'],
                    ['h2', 'Cutwork'],
                    ['p', 'Scalloped and cut-out edges create a delicate outline that reads as hand-finished from across the room.'],
                    ['quote', 'It is rarely the biggest detail that makes the outfit. It is the one you notice last.'],
                ],
            ],
            [
                'slug' => 'how-to-measure-yourself-at-home',
                'title' => 'How to measure yourself at home for the perfect fit',
                'category' => 'Guide',
                'date' => '2026-09-15',
                'read' => 4,
                'tone' => ['E3D9F6', '2D2E8E'],
                'excerpt' => 'All you need is a soft tape and ten minutes. Here is how to get numbers you can trust.',
                'body' => [
                    ['p', 'Good measurements make buying, and tailoring, so much easier. You only need a soft measuring tape and a mirror.'],
                    ['h2', 'Bust, waist and hips'],
                    ['p', 'Stand straight and relaxed. Measure around the fullest part of the bust, the narrowest part of the waist and the fullest part of the hips. Keep the tape snug, not tight.'],
                    ['h2', 'Length and sleeves'],
                    ['p', 'For kurta length, measure from the top of the shoulder down to where you want the hem to fall. For sleeves, measure from the shoulder point to the wrist.'],
                    ['p', 'Write everything down and compare with the size details on the product page. If you are between sizes, message us and we will help you choose.'],
                ],
            ],
            [
                'slug' => 'styling-a-simple-kurta-three-ways',
                'title' => 'One simple kurta, three ways to wear it',
                'category' => 'Style',
                'date' => '2026-09-02',
                'read' => 3,
                'tone' => ['FBE3EC', '8A2D5E'],
                'excerpt' => 'Day, dinner and a family function: one piece, three completely different moods.',
                'body' => [
                    ['p', 'A well-made kurta is the most versatile piece you can own. The trick is in what you pair it with.'],
                    ['h2', 'For the day'],
                    ['p', 'Keep it relaxed with straight trousers, flat sandals and a small bag. Let the embroidery do the talking.'],
                    ['h2', 'For dinner'],
                    ['p', 'Swap to a contrast shalwar or cigarette pants, add a dupatta and a pair of statement earrings.'],
                    ['h2', 'For a family function'],
                    ['p', 'Layer a rich dupatta, add heels and a clutch. The same kurta suddenly looks like a brand-new outfit.'],
                    ['quote', 'Great wardrobes are built on pieces that work harder, not on more pieces.'],
                ],
            ],
            [
                'slug' => 'choosing-fabric-for-pakistani-weather',
                'title' => 'Lawn, cotton or something heavier? Choosing for the weather',
                'category' => 'Guide',
                'date' => '2026-08-20',
                'read' => 5,
                'tone' => ['F8DCE8', '2D2E8E'],
                'excerpt' => 'A quick guide to picking the right fabric for Karachi heat and cooler evenings alike.',
                'body' => [
                    ['p', 'Fabric decides how an outfit feels long after the photos are taken. Here is a simple way to think about it.'],
                    ['h2', 'Lawn'],
                    ['p', 'Light and breathable, lawn is the go-to for warm days. It takes embroidery and prints beautifully.'],
                    ['h2', 'Cotton'],
                    ['p', 'A little more body and an easy everyday feel. Great for long days when you want comfort first.'],
                    ['h2', 'Heavier fabrics'],
                    ['p', 'For cooler evenings and occasions, richer fabrics hold structure and detail better. Save them for when the weather asks for it.'],
                ],
            ],
        ])->map(function ($p) {
            $o = (object) $p;
            $o->dt = Carbon::parse($p['date']);
            return $o;
        })
        ->sortByDesc('dt')
          ->values();
    }

    public function blogs()
    {
        $posts = $this->posts();
        $categories = $posts->pluck('category')->unique()->values();

        return view('shop.blogs', compact('posts', 'categories'));
    }

    public function show_blog(string $slug)
    {
        $posts = $this->posts();
        $i = $posts->search(fn ($p) => $p->slug === $slug);
        abort_if($i === false, 404);

        $post = $posts[$i];
        $newer = $posts[$i - 1] ?? null;
        $older = $posts[$i + 1] ?? null;
        $related = $posts->where('slug', '!=', $slug)
            ->sortByDesc(fn ($p) => $p->category === $post->category)
            ->take(3)->values();

        return view('shop.show_blog', compact('post', 'newer', 'older', 'related'));
    }


}