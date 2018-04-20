<?php
/**
 * Created by PhpStorm.
 * User: mabuhasna
 * Date: 6/8/2017
 * Time: 2:19 PM
 */

namespace App\Http\Controllers;


use App\Category;
use App\Comment;
use App\CommentOnComment;
use App\Photo;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PostController extends Controller
{
 public function getBlogIndex(){
     $posts = Post::paginate(5);
     foreach ($posts as $post){
         $post->body = $this->shorten($post->body,20);
     }
     $photos=array();

     foreach ($posts as $post){
         $photos[$post->id]=$post->photos;

     }

$tags = DB::select('SELECT name,tag_id,COUNT(post_id) as post_count from post_tags join tags where tag_id = tags.id GROUP BY name,tag_id ORDER BY post_count desc LIMIT 10');

     $categories_even = DB::select('select * from categories where id % 2 = 0');
     $categories_odd = DB::select('select * from categories where id % 2 != 0');
     return view('frontend.blog.index',['posts'=>$posts ,'categories_odd'=>$categories_odd , 'categories_even'=>$categories_even,'photos'=>$photos,'tags'=>$tags]);
 }
    public function getPostIndex(){
        $posts = Post::paginate(5);
        return view('admin.blog.index',['posts'=>$posts]);
    }
public function getSinglePost($post_id ,$end='frontend'){
    $post=Post::find($post_id);
    $routeTo=($end=='frontend')?'blog':'admin';
    if(!$post){
        return redirect()->route($routeTo.'.index')->with(['fail'=>'Post not found!']);
    }
    $categories=$post->categories;
    $post_categories_names=array();
    $i=0;
    foreach ($categories as $category){
        $post_categories_names[$i]=$category->name;
        $i++;
    }
    $comments= $post->comments;
    $commentsOnComments = array();
    foreach ($comments as $comment){
        $commentsOnComments[$comment->id]=$comment->commentsOnComment;
    }
    $photos=$post->photos;
    $post_tags=$post->tags;
    $post_tags_names=array();
    $i=0;
    foreach ($post_tags as $item){
        $post_tags_names[$i]=$item->name;
        $i++;
    }

    $tags = DB::select('SELECT name,tag_id,COUNT(post_id) as post_count from post_tags join tags where tag_id = tags.id GROUP BY name,tag_id ORDER BY post_count desc LIMIT 10');

    $categories_even = DB::select('select * from categories where id % 2 = 0');
    $categories_odd = DB::select('select * from categories where id % 2 != 0');
    return view($end.'.blog.single',['post'=>$post,'categories'=>$post_categories_names,'photos'=>$photos,'categories_odd'=>$categories_odd , 'categories_even'=>$categories_even,
        'tags'=>$tags,'post_tags_names'=>$post_tags_names,'comments'=>$comments,'commentsOnComments'=>$commentsOnComments]);
}
    public function getCreatePost(){
        $tags=Tag::all();
        $categories=Category::all();
        return view('admin.blog.create_post',['categories'=>$categories,'tags'=>$tags]);
    }
    public function postCreatePost(Request $request){

        $this->validate($request,[
            'title'=>'required|max:120|unique:posts',
            'author'=>'required|max:80',
            'body'=>'required'
        ]);

        $post = new Post();
        $post->title=$request['title'];
        $post->author=$request['author'];
        $post->body=$request['body'];
        $post->save();
if(strlen($request['categories']) > 0){
    $categoryIDs=explode(',',$request['categories']);
    foreach ($categoryIDs as $categoryID){
        $post->categories()->attach($categoryID);
    }
}
        if(strlen($request['tags']) > 0){
            $tags=explode(',',$request['tags']);
            foreach ($tags as $tag){
                $newTag=Tag::where('name',$tag)->first();
                if($newTag){
                    $post->tags()->attach($newTag->id);
                }else{
                    $new = new Tag();
                    $new->name=$tag;
                    $new->save();
                    $post->tags()->attach($new->id);
                }
            }
        }
        if($files=$request['postImg']){
            foreach($files as $file){
               //$name=$file->getClientOriginalName();
               $name= $file->store('image');
                //$file->move('image',$name);
                $photo = new Photo();
                $photo->name = $name;
                $photo->save();
                $post->photos()->attach($photo->id);
            }
        }
        return redirect()->route('admin.index')->with(['success'=>'Post Successfully Created!']);


    }
    public function getUpdatePost($post_id){

        $post=Post::find($post_id);
        if(!$post){
            return redirect()->route('admin.index')->with(['fail'=>'This Post was deleted or does not exist!']);
        }
        $categories=Category::all();
        $tags=Tag::all();
        $post_categories=$post->categories;
        $post_categories_ids=array();
        $i=0;
        foreach ($post_categories as $post_category){
            $post_categories_ids[$i]=$post_category->id;
            $i++;
        }
        $photos=$post->photos;
        $post_tags=$post->tags;
        $post_tags_names=array();
        $i=0;
        foreach ($post_tags as $item){
            $post_tags_names[$i]=$item->name;
            $i++;
        }
        if(!$post){
            return redirect()->route('blog.index')->with(['fail'=>'Post not found!']);
        }
        return view('admin.blog.edit_post',['post'=>$post,'categories'=>$categories,'post_categories'=>$post_categories,'post_categories_ids'=>$post_categories_ids,'post_tags_names'=>$post_tags_names,'tags'=>$tags,'photos'=>$photos]);

    }
    public function postUpdatePost(Request $request){

        $this->validate($request,[
            'title'=>'required|max:120',
            'author'=>'required|max:80',
            'body'=>'required'
        ]);

        $post = Post::find($request['post_id']);
        $post->title=$request['title'];
        $post->author=$request['author'];
        $post->body=$request['body'];
        $post->update();
        $post->categories()->detach();
        $post->tags()->detach();
        if(strlen($request['categories']) > 0){
            $categoryIDs=explode(',',$request['categories']);
            foreach ($categoryIDs as $categoryID){
                $post->categories()->attach($categoryID);
            }
        }
        if(strlen($request['tags']) > 0){
            $tags=explode(',',$request['tags']);
            foreach ($tags as $tag){
                $newTag=Tag::where('name',$tag)->first();
                if($newTag){
                    $post->tags()->attach($newTag->id);
                }else{
                    $new = new Tag();
                    $new->name=$tag;
                    $new->save();
                    $post->tags()->attach($new->id);
                }

            }
        }
        if($files=$request['postImg']){
            foreach($files as $file){
                //$name=$file->getClientOriginalName();
                $name= $file->store('image');
                //$file->move('image',$name);
                $photo = new Photo();
                $photo->name = $name;
                $photo->save();
                $post->photos()->attach($photo->id);
            }
        }
        return redirect()->route('admin.index')->with(['success'=>'Post Successfully Updated!']);

    }
    public function getDeletePost($post_id){

        $post=Post::find($post_id);
        if(!$post){
            return redirect()->route('admin.index')->with(['fail'=>'Post not found!']);
        }
        $post->delete();
        return redirect()->route('admin.index')->with(['success'=>'Post Successfully Deleted!']);

    }
    public function getCategoryPosts($category_id){
        $category=Category::find($category_id);
        $post_ids=DB::select('select post_id from posts_categories where category_id = '.$category->id);
        $ids=array();
        $i=0;
        foreach ($post_ids as $post_id){
           $ids[$i]=$post_id->post_id;
            $i++;
        }

        $posts=Post::whereIn('id', $ids)->paginate(3);

        foreach ($posts as $post){
            $post->body = $this->shorten($post->body,20);
        }
        $photos=array();

        foreach ($posts as $post){
            $photos[$post->id]=DB::table('photos as ph')
                ->join('post_photos as pph','ph.id','=','pph.photo_id')
                ->where('pph.post_id','=',$post->id)
                ->get();

        }
        $tags = DB::select('SELECT name,tag_id,COUNT(post_id) as post_count from post_tags join tags where tag_id = tags.id GROUP BY name,tag_id ORDER BY post_count desc LIMIT 10');

        $categories_even = DB::select('select * from categories where id % 2 = 0');
        $categories_odd = DB::select('select * from categories where id % 2 != 0');
        return view('frontend.blog.index',['posts'=>$posts ,'categories_odd'=>$categories_odd , 'categories_even'=>$categories_even , 'category'=>$category,'photos'=>$photos,'tags'=>$tags]);
    }
    public function getAuthorPosts($author){
       $posts=Post::where('author',$author)->paginate(3);


        foreach ($posts as $post){
            $post->body = $this->shorten($post->body,20);
        }
        $photos=array();

        foreach ($posts as $post){
            $photos[$post->id]=$post->photos;

        }
        $tags = DB::select('SELECT name,tag_id,COUNT(post_id) as post_count from post_tags join tags where tag_id = tags.id GROUP BY name,tag_id ORDER BY post_count desc LIMIT 10');

        $categories_even = DB::select('select * from categories where id % 2 = 0');
        $categories_odd = DB::select('select * from categories where id % 2 != 0');
        return view('frontend.blog.index',['posts'=>$posts ,'categories_odd'=>$categories_odd , 'categories_even'=>$categories_even , 'author'=>$author,'photos'=>$photos,'tags'=>$tags]);
    }
    public function getTagPosts($tag_id){
        $tag = Tag::find($tag_id);
        $post_ids=DB::select('select post_id from post_tags where tag_id = '.$tag->id);
        $ids=array();
        $i=0;
        foreach ($post_ids as $post_id){
            $ids[$i]=$post_id->post_id;
            $i++;
        }

        $posts=Post::whereIn('id', $ids)->paginate(3);

        foreach ($posts as $post){
            $post->body = $this->shorten($post->body,20);
        }
        $photos=array();

        foreach ($posts as $post){
            $photos[$post->id]=DB::table('photos as ph')
                ->join('post_photos as pph','ph.id','=','pph.photo_id')
                ->where('pph.post_id','=',$post->id)
                ->get();

        }
        $tags = DB::select('SELECT name,tag_id,COUNT(post_id) as post_count from post_tags join tags where tag_id = tags.id GROUP BY name,tag_id ORDER BY post_count desc LIMIT 10');

        $categories_even = DB::select('select * from categories where id % 2 = 0');
        $categories_odd = DB::select('select * from categories where id % 2 != 0');
        return view('frontend.blog.index',['posts'=>$posts ,'categories_odd'=>$categories_odd , 'categories_even'=>$categories_even , 'tag'=>$tag,'photos'=>$photos,'tags'=>$tags]);

    }
    public function getSearchPosts(Request $request){
         $search=$request['search'];
      //  $posts=Post::where('title', '%'.$search.'%')->paginate(3);
//        $posts=Post::where(function ($query) {
//
//                $query->Where('title', 'LIKE', '%'.$request['search'].'%');
//                $query->orWhere('author', 'LIKE', '%'.$search.'%');
//                $query->orWhere('body', 'LIKE', '%'.$search.'%');
//
//        })->paginate(3);
        $post = Post::query();
        $post->Where('title', 'LIKE', '%'.$search.'%');
        $post->orWhere('author', 'LIKE', '%'.$search.'%');
        $post->orWhere('body', 'LIKE', '%'.$search.'%');
        $posts = $post->paginate(3);
        foreach ($posts as $post){
            $post->body = $this->shorten($post->body,20);
        }
        $photos=array();

        foreach ($posts as $post){
            $photos[$post->id]=DB::table('photos as ph')
                ->join('post_photos as pph','ph.id','=','pph.photo_id')
                ->where('pph.post_id','=',$post->id)
                ->get();

        }
        $tags = DB::select('SELECT name,tag_id,COUNT(post_id) as post_count from post_tags join tags where tag_id = tags.id GROUP BY name,tag_id ORDER BY post_count desc LIMIT 10');

        $categories_even = DB::select('select * from categories where id % 2 = 0');
        $categories_odd = DB::select('select * from categories where id % 2 != 0');
        return view('frontend.blog.index',['posts'=>$posts ,'categories_odd'=>$categories_odd , 'categories_even'=>$categories_even ,'photos'=>$photos,'tags'=>$tags]);

    }
    function getDeletePostPhoto($post_id,$photo_id){
        $post=Post::find($post_id);
        $photo=Photo::find($photo_id);

        if($post && $photo){

                $post->photos()->detach($photo_id);
                $photo->delete();
                $msg='Photo deleted';
                $staus=200;
            return Response::json(['message'=>$msg],$staus);


        }else{
            $msg='Choose Avalid post image';
            $staus=400;
            return Response::json(['message'=>$msg],$staus);
        }

        

    }
    public function postCommentOnPost(Request $request){
        if($request->ajax()){
            $post_id=$request['post_id'];
            $post=Post::find($post_id);
            if($post){
                if(!isset($request['name'])|| !isset($request['username']) ){
                    return Response::json(['status'=>false],400);
                }
                $comment=new Comment();
                $comment->post_id=$request['post_id'];
                $comment->name = $request['name'];
                $comment->username=$request['username'];
                $comment->save();
                return Response::json(['status'=>true],200);
            }

        }
        return redirect()->route('blog.index');
    }
    public function postCommentOnComment(Request $request){
        if($request->ajax()){
            $comment_id=$request['comment_id'];
            $comment=Comment::find($comment_id);
            if($comment){
                if(!isset($request['name'])|| !isset($request['username']) ){
                    return Response::json(['status'=>false],400);
                }
                $commentOnComment=new  CommentOnComment();
                $commentOnComment->comment_id=$request['comment_id'];
                $commentOnComment->name = $request['name'];
                $commentOnComment->username=$request['username'];
                $commentOnComment->save();
                return Response::json(['status'=>true],200);
            }
        }
        return redirect()->route('blog.index');
    }
    public function shorten($text,$words_count){

        if(str_word_count($text,0) > $words_count){
            $words= str_word_count($text,2);
            $pos=array_keys($words);
            $text = substr($text,0,$pos[$words_count]).'...';

        }
        return $text;
    }
}