<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Create a subscription for the authenticated user
     *
     * @param  Channel  $channel
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(Channel $channel)
    {
        return $channel->subscriptions()->create([
            'user_id' => auth()->user()->id,
        ]);
    }

    /**
     * Delete subscription
     *
     * @param  Channel  $channel
     * @param  Subscription  $subscription
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Channel $channel, Subscription $subscription)
    {
        $subscription->delete();

        return response()->json([]);
    }
}
