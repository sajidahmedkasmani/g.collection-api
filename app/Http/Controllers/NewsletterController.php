<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
        ]);
        $email = Str::lower($data['email']);
        $subscriber = NewsletterSubscriber::firstOrNew(['email' => $email]);

        if ($subscriber->exists && $subscriber->isSubscribed()) {
            $message = 'This email is already subscribed.';
        } else {
            $subscriber->fill([
                'unsubscribe_token' => Str::random(64),
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ])->save();
            $message = 'Thanks for subscribing to our newsletter.';
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return back()->with('success', $message);
    }

    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();
        $subscriber->update(['unsubscribed_at' => now()]);

        return response()->view('frontend.newsletter-unsubscribed');
    }
}
