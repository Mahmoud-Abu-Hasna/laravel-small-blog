<?php
/**
 * Created by PhpStorm.
 * User: mabuhasna
 * Date: 6/14/2017
 * Time: 10:34 AM
 */

namespace App\Http\Controllers;


use App\ContactMessage;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class ContactMessageController extends Controller
{
public function getContactIndex(){
    $tags = DB::select('SELECT name,tag_id,COUNT(post_id) as post_count from post_tags join tags where tag_id = tags.id GROUP BY name,tag_id ORDER BY post_count desc LIMIT 10');

    $categories_even = DB::select('select * from categories where id % 2 = 0');
    $categories_odd = DB::select('select * from categories where id % 2 != 0');
    return view('frontend.other.contact',['tags'=>$tags,'categories_even'=>$categories_even,'categories_odd'=>$categories_odd]);
}
    public function getAboutPage(){
        $tags = DB::select('SELECT name,tag_id,COUNT(post_id) as post_count from post_tags join tags where tag_id = tags.id GROUP BY name,tag_id ORDER BY post_count desc LIMIT 10');

        $categories_even = DB::select('select * from categories where id % 2 = 0');
        $categories_odd = DB::select('select * from categories where id % 2 != 0');
        return view('frontend.other.about',['tags'=>$tags,'categories_even'=>$categories_even,'categories_odd'=>$categories_odd]);
    }
    public function postSendMessage(Request $request){
        $this->validate($request,[
            'email'=>'required|email',
            'name'=>'required|max:100',
            'subject'=>'required|max:140',
            'message'=>'required|min:10'
        ]);
        $message = new ContactMessage();
        $message->email=$request['email'];
        $message->sender=$request['name'];
        $message->subject=$request['subject'];
        $message->body=$request['message'];
        $message->save();
        Event::fire(new MessageSent($message));
        return redirect()->route('contact')->with(['success'=>'Message successfully sent!']);
    }
    public function getContactMessageIndex(){
        $messages = ContactMessage::orderBy('created_at','desc')->paginate(5);
        return view('admin.other.contact_messages',['contact_messages'=>$messages]);
    }
    public function getDeleteMessage($message_id){
        $contact_messages=ContactMessage::find($message_id);
        $contact_messages->delete();
        return Response::json(['message'=>'Message Deleted'],200);
    }
}