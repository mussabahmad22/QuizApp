<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use App\Models\Answer;
use App\Models\Categoury;
use App\Models\Exercise;
use App\Models\Question;
use App\Models\User;
use App\Models\Blog;
use App\Models\blog_category;
use App\Models\Bookmark;
use App\Models\Post;
use App\Models\Quiz;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Result;


use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function users(){

        $users =  User::where('role', 'user')->get();
        return view('admin.users',compact('users'));
    }

    public function show_user_form(){

        $url = url('add_users');
        $title = 'ADD NEW USER';
        $text = 'Save';
        return view('admin.add_users',['url'=>$url ,'title'=>$title ,'text'=>$text]);
    }

    public function add_users(Request $request){

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        $users = new User();
        $users->name = $request->name;
        $users->email = $request->email;
        $users->password = Hash::make($request->password);
        $users->save();
        return redirect(route('users',compact('users')))->with('success', 'User Added successfully');

    }

    public function delete_user(Request $request){

        $user_id = $request->delete_user_id;
        $del_ans = Answer::where('user_id',  $user_id);
        $del_ans->delete();
        $del_bookmark = Bookmark::where('user_id', $user_id);
        $del_bookmark->delete();
        $del_quiz = Quiz::where('user_id', $user_id);
        $del_quiz->delete();
        $del_result = Result::where('user_id',  $user_id);
        $del_result->delete();
        $users = User::findOrFail($user_id);
        $users->delete();
        return redirect(route('users'))->with('error', 'User Deleted successfully');
    }

    public function edit_user($id){

        $record = User::find($id);
        $url = url('update_user') ."/". $id;
        $title = 'EDIT USER';
        $text = 'Update';
        return view('admin.add_users',['record'=> $record , 'url'=>$url ,'title'=>$title ,'text'=>$text]);

    }
    public function update_user($id, Request $request){

        $users = User::findOrFail($id);
        $users->name = $request->name;
        $users->email = $request->email;
        $users->password = $request->password;
        $users->save();
        return redirect(route('users'))->with('success', 'User Update successfully');

    }

    public function logout(){

        Session::flush();
        Auth::logout();
        return redirect('login');
    }

    public function categoury(){

        $categoury = Categoury::all();
        return view('admin.categoury',compact('categoury'));

    }

    public function add_categoury(Request $request){

        $request->validate([
            'name' => 'required',
        ]);

        $categoury = new Categoury();
        $categoury->name = $request->name;
        $categoury->save();
        return redirect(route('categoury',compact('categoury')))->with('success', 'Categoury Added successfully');

    }

    public function categouries(){

        $categouries = Categoury::all();
        return view('admin.categouries',compact('categouries'));

    }

    public function edit_categoury($id){

        $categoury = Categoury::find($id);
        return response()->json([
            'status' => '200',
            'categoury' => $categoury,
        ]);
    }

    public function categoury_update(Request $request){

        $cat_id = $request->cat_id;
        $categoury = Categoury::findOrFail($cat_id);
        $categoury->name = $request->cat_name;
        $categoury->save();
        return redirect()->back()->with('success', 'Categoury Updated successfully');

    }

    public function categoury_delete(Request $request){
        
        $categoury_id = $request->delete_categoury_id;

        $del_bookmark = Bookmark::where('cat_id', $categoury_id);
        $del_bookmark->delete();
        $del_exe = Exercise::where('categoury_id', $categoury_id)->first();
        $del_ques = Question::where('exercise_id', $del_exe->id);
        $del_ques->delete();
        $del_ques = Result::where('cat_id', $categoury_id);
        $del_ques->delete();
        $del_exe->delete();
        
        $categoury = Categoury::findOrFail($categoury_id);
        $categoury->delete();
        return redirect()->back()->with('error', 'Categoury Deleted successfully');
        
    }


    public function exercise($id){

        $exe = Exercise::all();
        $cat = Categoury::find($id);
        $query = DB::select('select * from exercises where categoury_id ='. $id);
        return view('admin.exercise',compact('cat','exe','query'));

    }

    public function add_exercise($id, Request $request){

        $request->validate([
            'exe_name' => 'required',
            'time_duration' => 'required',
        ]);

        $exercise = new Exercise;
        $exercise->categoury_id =  $request->id;
        $exercise->exercise_name = $request->exe_name;
        $exercise->time_duration = $request->time_duration;
        $exercise->save();
        return redirect(route('exercise',$id))->with('success', 'Exercise Added successfully');
    }

    public function edit_exercise($id){

        $exercise = Exercise::findOrFail($id);
        return response()->json([
            'status' => '200',
            'exercise' => $exercise,
        ]);

    }

    public function exercise_update(Request $request){

        $query_id = $request->query_id;

        $exercise = Exercise::findOrFail($query_id);
        $exercise->exercise_name = $request->exe_name;
        $exercise->time_duration = $request->time_duration;
        $exercise->save();
        return redirect()->back()->with('success', 'Exercise Updated successfully');

    }

    public function exercise_delete(Request $request){
        
       
        $query_id = $request->delete_exercise_id;
        $del_ques = Question::where('exercise_id', $query_id);
        $del_ques->delete();
        $del_result = Result::where('exercise_id', $query_id);
        $del_result->delete();
        $exercise = Exercise::findOrFail($query_id);
        $exercise->delete();
        return redirect()->back()->with('error', 'Exercise Deleted successfully');
        
    }

    public function questions($id){


        $questions_all = Question::all();
        $exe = Exercise::find($id);
        $db_query = DB::select('select * from questions where exercise_id ='. $id);
        return view('admin.questions',compact('questions_all','exe','db_query'));

    }

    public function show_add_question($id){

        $exe = Exercise::find($id);
        $url = url('add_questions') . "/" . $id;
        $title = 'ADD NEW QUESTION';
        $text = 'Save';
        return view('admin.add_questions',compact('exe','url','title','text'));

    }

    public function add_questions($id, Request $request){

        //dd($request);

        if ($request->file('file_title') == null) {
            $path_title = "";
        }else{
            $path_title = $request->file('file_title')->store('public/images');  
        }

        if ($request->file('file_reveiw') == null) {
            $path_review = "";
        }else{
            $path_review = $request->file('file_reveiw')->store('public/images');
        }

        // $path_title = $request->file('file_title')->store('public/images');
        // $path_review = $request->file('file_review')->store('public/images');

        $request->validate([
            'question_title' => 'required',
            'option1' => 'required',
            'statement1' => 'required',
            'option2' => 'required',
            'statement2' => 'required',
            'option3' => 'required',
            'statement3' => 'required',
            'option4' => 'required',
            'statement4' => 'required',
            'options' => 'required',
            'right_answer_statement' => 'required',
            'link' => 'required',
        ]);

        $questions = new Question();
        $questions->exercise_id =  $request->id;
        $questions->question_title = $request->question_title;
        $questions->path_title = $path_title;
        $questions->option_1 = $request->option1;
        $questions->statement_1 = $request->statement1;
        $questions->option_2 = $request->option2;
        $questions->statement_2 = $request->statement2;
        $questions->option_3 = $request->option3;
        $questions->statement_3 = $request->statement3;
        $questions->option_4 = $request->option4;
        $questions->statement_4 = $request->statement4;
        $questions->right_ans = $request->options;
        $questions->right_ans_statement = $request->right_answer_statement;
        $questions->question_review = $request->question_review;
        $questions->path_review = $path_review;
        $questions->yt_link = $request->link;
        $questions->save();
        return redirect(route('questions',$id))->with('success', 'Question Added successfully');
        
    }

    public function edit_question_page($id){

        $ques = Question::find($id);
        $exe = Exercise::find($ques->exercise_id);
        $url = url('question_update') ."/". $id;
        $title = 'EDIT QUESTION';
        $text = 'Update';
        return view('admin.add_questions',compact('exe','ques','url','title','text'));

    }

    public function question_update($id ,Request $request){


        // $path_title = $request->file('file_title')->store('public/images');
        // $path_review = $request->file('file_review')->store('public/images');

        $question = Question::findOrFail($id);

        if ($request->file('file_title') == null) {
            $path_title = $question->path_title;
        }else{
            $path_title = $request->file('file_title')->store('public/images');  
        }

        if ($request->file('file_reveiw') == null) {
            $path_review = $question->path_review;
        }else{
            $path_review = $request->file('file_reveiw')->store('public/images');
        }
        
        $question->exercise_id = $request->id;
        $question->question_title = $request->question_title;
        $question->path_title = $path_title;
        $question->option_1 = $request->option1;
        $question->statement_1 = $request->statement1;
        $question->option_2 = $request->option2;
        $question->statement_2 = $request->statement2;
        $question->option_3 = $request->option3;
        $question->statement_3 = $request->statement3;
        $question->option_4 = $request->option4;
        $question->statement_4 = $request->statement4;
        $question->right_ans = $request->options;
        $question->right_ans_statement = $request->right_answer_statement;
        $question->question_review = $request->question_review;
        $question->path_review = $path_review;
        $question->yt_link = $request->link;
        $question->save();
        return redirect(route('questions', $question->exercise_id))->with('success', 'Question Updated successfully');  

    }

    public function question_delete(Request $request){
        
        $query_id = $request->delete_question_id;
        $question = Question::findOrFail($query_id);
        $question->delete();
        return redirect()->back()->with('error', 'Question Deleted successfully');
        
    }

    public function blog(){

        $blog = blog_category::all();
        return view('admin.blog',compact('blog'));

    }

    public function add_blog(Request $request){

        $request->validate([
            'name' => 'required',
        ]);

        $blog = new blog_category();
        $blog->blog_name = $request->name;
        $blog->save();
        return redirect(route('blog',compact('blog')))->with('success', 'Blog Added successfully');

    }

    public function edit_blog($id){

        $blog = blog_category::find($id);
        return response()->json([
            'status' => '200',
            'blog' => $blog,
        ]);
    }

    public function blog_update(Request $request){

        $blog_id = $request->blog_id;
        $blog = blog_category::findOrFail($blog_id);
        $blog->blog_name = $request->blog_name;
        $blog->save();
        return redirect()->back()->with('success', 'Blog Updated successfully');

    }

    public function blog_delete(Request $request){
        
        $blog_id = $request->delete_blog_id;
        $del_blog = Blog::where('cat_id',  $blog_id );
        $del_blog->delete();
        $categoury = blog_category::findOrFail($blog_id);
        $categoury->delete();
        return redirect()->back()->with('error', 'Blog Deleted successfully');
        
    }

    public function blogs(){

        $blogs = blog_category::all();
        return view('admin.blogs',compact('blogs'));

    }

    public function blog_posts($id){

        $blog_posts_all = Blog::all();
        $blog_category = blog_category::find($id);
        $query = DB::select('select * from blogs where cat_id ='. $id);
        return view('admin.blog_posts',compact('blog_posts_all','blog_category','query'));

    }

    public function show_add_blog_posts($id){

        $cat = blog_category::find($id);
        $url = url('add_blog_posts')  ."/". $id;
        $title = 'ADD NEW BLOG';
        $text = 'Save';
        return view('admin.add_blog_post',compact('cat','url','title','text'));

    }

    public function add_blog_posts($id, Request $request){

        $request->validate([
            'post_title' => 'required',
            'desc' => 'required',
        ]);

        $blog = new Blog();
        $blog->cat_id =  $request->id;
        $blog->post_title = $request->post_title;
        $blog->post_desc = $request->desc;
        $blog->save();
        return redirect(route('blog_posts',$id))->with('success', 'Blog Added successfully');

    }

    public function edit_blog_posts($id){

        $post = Blog::find($id);
        $cat = blog_category::find($post->cat_id);
        $url = url('update_blog_posts') ."/". $id;
        $title = 'EDIT BLOG POST';
        $text = 'Update';
        return view('admin.add_blog_post',['cat'=>$cat, 'post'=> $post , 'url'=>$url ,'title'=>$title ,'text'=>$text]);

    }

    public function update_blog_posts($id, Request $request){

        $blog = Blog::findOrFail($id);
        $blog->cat_id =  $request->id;
        $blog->post_title = $request->post_title;
        $blog->post_desc = $request->desc;
        $blog->save();
        return redirect(route('blog_posts', $blog->cat_id))->with('success', 'Blog Updated successfully');
     
    }

    public function blog_post_delete(Request $request){
        
        $blog_post_id = $request->delete_blog_post_id;
        $post = Blog::findOrFail($blog_post_id);
        $post->delete();
        return redirect()->back()->with('error', 'Blog Post Deleted successfully');
        
    }

    public function show_blog_post($id){

        $blog_posts = Blog::find($id);
        $blog_category = blog_category::find($id);
        return view('admin.show_blog_post',compact('blog_posts','blog_category'));

    }

}
