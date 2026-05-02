<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GraceChatController extends Controller
{
    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $message   = $request->input('message');
        $sessionId = session()->getId();

        try {
            $response = Http::timeout(30)->post(config('services.n8n.webhook_url'), [
                'chatInput' => $message,
                'sessionId' => $sessionId,
            ]);

            $reply = $response->json('reply')
                ?? $response->json('output')
                ?? "I'm sorry, I couldn't process that right now. Please try again.";

        } catch (\Exception $e) {
            $reply = "I'm having trouble connecting right now. Please try again in a moment.";
        }

        return response()->json(['reply' => $reply]);
    }
}
