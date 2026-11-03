<!doctype html>
<html lang="en">
<body style="font-family:Arial,sans-serif;color:#211b24;line-height:1.6;margin:0;padding:24px">
    <main style="max-width:640px;margin:auto">
        <h1 style="font-size:26px">{{ $campaign->title }}</h1>
        <div style="white-space:pre-wrap">{{ $campaign->content }}</div>
        <hr style="border:0;border-top:1px solid #eee;margin:32px 0 16px">
        <p style="font-size:12px;color:#666">
            You received this email because you subscribed to G. Collection updates.
            <a href="{{ $unsubscribeUrl }}">Unsubscribe</a>
        </p>
    </main>
</body>
</html>
