<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Models\Channel;

class ChannelController extends Controller
{

    /**
     * ChannelController constructor.
     *
     * The update route is restricted to authenticated users
     */
    public function __construct()
    {
        $this->middleware('auth')->only('update');
    }

    /**
     * Display the channel
     *
     * @param  Channel  $channel
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Channel $channel)
    {
        $videos = $channel->videos()->paginate(5);

        return view('channels.show', compact('channel', 'videos'));
    }

    /**
     * Update a channel
     *
     * @param  UpdateChannelRequest  $request
     * @param  Channel  $channel
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function update(UpdateChannelRequest $request, Channel $channel)
    {

        //handle the image / thumbnail part
        if ($request->hasFile('image')) {
            $channel->clearMediaCollection();

            $channel->addMediaFromRequest('image')
                    ->toMediaCollection();
        }

        $channel->update([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back();
    }
}
