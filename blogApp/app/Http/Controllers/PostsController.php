<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Category;
use App\Post;
use App\Like;
use Auth;
use URL;


class PostsController extends Controller
{
    public function post(){
      $categories = Category::all();

      return view('posts.post', ['categories' => $categories]);
    }
    public function addPost(Request $request){
    $this->validate($request, [
      'post_title' => 'required',
      'post_body' => 'required',
      'category_id' => 'required',
      'post_image' => 'required'
    ]);

    $posts = new Post;
    $posts->post_title = $request->input('post_title');
    $posts->user_id = Auth::user()->id;
    $posts->post_body = $request->input('post_body');
    $posts->category_id = $request->input('category_id');

    if(Input::hasFile('post_image')){
      $file = Input::file('post_image');
      $file->move(public_path().'/posts',
      $file->getClientOriginalName());
      $url = URL::to("/") .'/posts/' .
      $file->getClientOriginalName();
    }
    $posts->post_image = $url;
    $posts->save();
    return redirect('/home')->
    with('response', 'Posts Added Successfully!');
    }

    public function view($post_id){
      $posts = Post::where('id', '=', $post_id)->get();
      $likePost = Post::find($post_id);
      $likeCtr = Like::where(['post_id' => $likePost->id])->get();
      return $likeCtr;
      exit();
      $categories = Category::all();
      return view('posts.view', ['posts'=> $posts, 'categories' => $categories]);
    }
    public function edit($post_id){
      $categories = Category::all();
      $posts = Post::find($post_id);
      $category = Category::find($posts->category_id);
      return view('posts.edit', ['categories' => $categories, 'posts' => $posts, 'category' => $category]);
    }
    public function editPost(Request $request, $post_id){
      $this->validate($request, [
        'post_title' => 'required',
        'post_body' => 'required',
        'category_id' => 'required',
        'post_image' => 'required'
      ]);

      $posts = new Post;
      $posts->post_title = $request->input('post_title');
      $posts->user_id = Auth::user()->id;
      $posts->post_body = $request->input('post_body');
      $posts->category_id = $request->input('category_id');

      if(Input::hasFile('post_image')){
        $file = Input::file('post_image');
        $file->move(public_path().'/posts',
        $file->getClientOriginalName());
        $url = URL::to("/") .'/posts/' .
        $file->getClientOriginalName();
      }
      $posts->post_image = $url;
      $data = array(
        'post_title' => $posts->post_title,
        'user_id' => $posts->user_id,
        'post_body' => $posts->post_body,
        'post_title' => $posts->category_id,
        'post_image' => $posts->post_image
      );
      Post::where('id', $post_id)
      ->update($data);
      $posts->update();
      return redirect('/home')->with('response', 'Posts Updated Successfully!');

    }

    public function deletePost($post_id){
      Post::where('id', $post_id)->delete();
      return redirect('/home')->with('response', 'Post Deleted Successfully');

    }

    public function category($cat_id){
      $categories = Category::all();
      $posts = DB::table('posts')
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->select('posts.*', 'categories.*')
            ->where(['categories.id' => $cat_id])
            ->get();

      return view('categories.categoriesposts', ['categories' => $categories, 'posts' => $posts]);
    }

    public function like($id){
      $loggedin_user = Auth::user()->id;
      $like_user = Like::where(['user_id' => $loggedin_user, 'post_id' => $id])->first();
      if(empty($like_user->user_id)){
        $user_id = Auth::user()->id;
        $email = Auth::user()->email;
        $post_id = $id;
        $like = new Like;
        $like->user_id = $user_id;
        $like->email = $email;
        $like->post_id = $post_id;
        $like->save();
        return redirect("/view/{id}");
      }
      else {
        return redirect("/view/{$id}");
      }
    }

}
