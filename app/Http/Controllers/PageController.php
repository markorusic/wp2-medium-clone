<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Post, Category};
use App\Http\Requests\{ContactRequest};

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() {
        $categories = Category::take(6)->get();
        $posts = Post::with('user')->paginate(6);
        $popular_posts = Post::popular()->with('user')->take(10)->get();
        return view('public.pages.home', compact('categories', 'posts', 'popular_posts'));
    }

    public function contact(ContactRequest $request) {
        $data = $request->all();
        $recipient = env('CONTACT_MAIL_RECIPIENT', null);
        $sender = env('CONTACT_MAIL_SENDER', null);

        try {
            \Mail::send(
                'public.email.contact',
                compact('data'),
                function ($message) use ($data, $sender, $recipient) {
                    $message->from($sender, 'Contact');
                    $message->to($recipient);
                    $message->subject($data['subject']);
                }
            );
        } catch (\Throwable $th) {}


        return response('Success', 200);
    }
}
