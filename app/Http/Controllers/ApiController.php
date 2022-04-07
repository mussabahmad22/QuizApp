<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use App\Models\Categoury;
use App\Models\Exercise;
use App\Models\Question;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Bookmark;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\blog_category;
use App\Models\Blog;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    //================== Get All Users Api ================================
    public function users()
    {
        $users = User::get();

        if (is_null($users)) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            $res['data'] = [];
            return response()->json($res, 404);
        } else {
            $res['status'] = true;
            $res['message'] = "Users List";
            $res['data'] = User::get();
            return response()->json($res, 200);
        }
    }
    //================== Get Users by Id Api ==============================
    public function users_id($id)
    {
        $user = User::find($id);

        if (is_null($user)) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            $res['data'] = [];
            return response()->json($res, 404);
        } else {

            $res['status'] = true;
            $res['message'] = "Users List";
            $res['data'] = User::find($id);
            return response()->json($res, 200);
        }
    }
    //================== Add Users Api ====================================
    public function add_users(Request $request)
    {
        $rules = [
            'name' => 'required | min:5',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ];

        $validator = FacadesValidator::make($request->all(), $rules);
        if ($validator->fails()) {
            $err = $validator->errors()->getMessages();
            $msg = array_values($err)[0][0];
            $res['status'] = false;
            $res['message'] = $msg;

            return response()->json($res, 400);
        }
        $request['password'] = Hash::make($request['password']);
        // $request['remember_token'] = Str::random(10);
        $users = User::create($request->all());
        if (is_null($users)) {

            $res['status'] = false;
            $res['message'] = "User Can't Insert Sucessfully";
            $res['data'] = [];
            return response()->json($res, 404);
        } else {

            $res['status'] = true;
            $res['message'] = "User Insert Sucessfully";
            $res['data'] = $users;
            return response()->json($res, 200);
        }
        return response()->json($users, 201);
    }
    //================== Update Users Api ==================================
    public function update_user($id, Request $request)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'Record Not Found!'], 404);
        }
        $rules = [
            'name' => 'required | min:5',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ];

        $validator = FacadesValidator::make($request->all(), $rules);
        if ($validator->fails()) {
            $err = $validator->errors()->getMessages();
            $msg = array_values($err)[0][0];
            $res['status'] = false;
            $res['message'] = $msg;

            return response()->json($res, 400);
        }

        $user->update($request->all());
        if (is_null($user)) {

            $res['status'] = false;
            $res['message'] = "User Can't Update Sucessfully";
            $res['data'] = [];
            return response()->json($res, 404);
        } else {

            $res['status'] = true;
            $res['message'] = "User Update Sucessfully";
            $res['data'] = $user;
            return response()->json($res, 200);
        }
        return response()->json($user, 200);
    }
    //================== Delete Users Api ==================================
    public function delete_user($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'Record Not Found!'], 404);
        }
        $user->delete();
        return response()->json($user, 200);
    }
    //================== Get All Category Api ================================
    public function catgory()
    {
        $category = Categoury::select('id', 'name')->get();
        if (is_null($category)) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            $res['data'] = [];
            return response()->json($res, 404);
        } else {
            $res['status'] = true;
            $res['message'] = "Category List!";
            $res['data'] =  $category;
            return response()->json($res, 200);
        }
    }
    //================== Get Category by Id Api ==============================
    public function category_id($id)
    {
        $category = Categoury::find($id);
        if (is_null($category)) {
            return response()->json(['message' => 'Record Not Found!'], 404);
        }
        return response()->json($category, 200);
    }
    //================== Add Category Api =====================================
    public function add_category(Request $request)
    {
        $rules = [
            'name' => 'required | min:2',
        ];

        $validator = FacadesValidator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $category = Categoury::create($request->all());
        return response()->json($category, 201);
    }
    //================== Update Category Api ==================================
    public function update_category($id, Request $request)
    {
        $category = Categoury::find($id);
        if (is_null($category)) {
            return response()->json(['message' => 'Record Not Found!'], 404);
        }
        $rules = [
            'name' => 'required | min:2',
        ];

        $validator = FacadesValidator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $category->update($request->all());
        return response()->json($category, 200);
    }
    //================== Delete Category Api ==================================
    public function delete_category($id)
    {
        $category = Categoury::find($id);
        if (is_null($category)) {
            return response()->json(['message' => 'Record Not Found!'], 404);
        }
        $category->delete();
        return response()->json($category, 200);
    }
    //=============== Get All exercises against category by id Api =================
    public function exercise($id)
    {
        $cat = Categoury::find($id);
        $exe = Exercise::where('categoury_id', $id)->count();
        $query = DB::select('select exercise_name, time_duration from exercises where categoury_id =' . $id);

        if (count($query) == 0) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            $res['data'] = [];
            return response()->json($res, 404);
        } else {

            $res['status'] = true;
            $res['message'] = "Exercise List!";
            $res['data']['category_id'] =  $cat->id;
            $res['data']['category_name'] =  $cat->name;
            $res['data']['total_exercises'] = $exe;
            $res['data']['exercises'] = $query;

            return response()->json($res, 200);
        }
    }
    //=============== Get All Questions against exercise by id Api =================
    public function questions($id)
    {
        $exe = Exercise::find($id);
        $ques = Question::where('exercise_id', $id)->count();
        $query = DB::select('select * from questions where exercise_id =' . $id);

        if (count($query) == 0) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            $res['data'] = [];
            return response()->json($res, 404);
        } else {

            $res['status'] = true;
            $res['message'] = "Questions List!";
            $res['data']['exercise_id'] =  $exe->id;
            $res['data']['exercise_name'] =  $exe->exercise_name;
            $res['data']['total_questions'] = $ques;
            $res['data']['questions'] = $query;
            return response()->json($res, 200);
        }
    }

    public function quiz_start(Request $request, $user_id, $exe_id)
    {
        $query = Quiz::where('user_id', $user_id)->where('exercise_id', $exe_id)->get();


        if (count($query) == 0) {

            $user = User::find($user_id);
            if (is_null($user)) {

                $res['status'] = false;
                $res['message'] = "User Not Found!";
                $res['data'] = [];
                return response()->json($res, 404);
            }

            $exe = Exercise::find($exe_id);
            if (is_null($exe)) {

                $res['status'] = false;
                $res['message'] = "Exercise Not Found!";
                $res['data'] = [];
                return response()->json($res, 404);
            }

            $questions = Question::where('exercise_id', $exe_id)->get();
            if (count($questions) == 0) {

                $res['status'] = false;
                $res['message'] = "Questions Not Found!";
                $res['data'] = [];
                return response()->json($res, 404);
            }

            $current_time = Carbon::now()->format('H:i:m');

            if ($exe->time_duration == 30) {

                $end_time = Carbon::now()->addMinutes(30)->format('H:i:m');
            }
            if ($exe->time_duration == 60) {

                $end_time = Carbon::now()->addMinutes(60)->format('H:i:m');
            }
            if ($exe->time_duration == 90) {

                $end_time = Carbon::now()->addMinutes(90)->format('H:i:m');
            }
            if ($exe->time_duration == 120) {

                $end_time = Carbon::now()->addMinutes(120)->format('H:i:m');
            }
            if ($exe->time_duration == 150) {

                $end_time = Carbon::now()->addMinutes(150)->format('H:i:m');
            }
            if ($exe->time_duration == 180) {

                $end_time = Carbon::now()->addMinutes(180)->format('H:i:m');
            }
            if ($exe->time_duration == 210) {

                $end_time = Carbon::now()->addMinutes(210)->format('H:i:m');
            }
            if ($exe->time_duration == 240) {

                $end_time = Carbon::now()->addMinutes(240)->format('H:i:m');
            }

            $quiz = new Quiz();
            $quiz->user_id = $user_id;
            $quiz->exercise_id = $exe_id;
            $quiz->start_time = $current_time;
            $quiz->end_time = $end_time;
            $quiz->save();

            if (is_null($quiz)) {

                $res['status'] = false;
                $res['message'] = "Quiz Can't created!";
                $res['data'] = [];
                return response()->json($res, 404);
            } else {

                $question_list = array();
                foreach ($questions as $ques) {

                    $ques_id = $ques->id;

                    $bookmark = Bookmark::where('user_id', $user_id)->where('exercise_id', $exe_id)->where('question_id', $ques_id)->count();

                    if ($bookmark == 1) {

                        $ques->bookmark_status = true;
                    } else {

                        $ques->bookmark_status = false;
                    }

                    array_push($question_list, $ques);
                }

                $res['status'] = true;
                $res['message'] = "Quiz!";
                $res['data']['user_id'] = $user->id;
                $res['data']['user_name'] = $user->name;
                $res['data']['exercise_id'] =  $exe->id;
                $res['data']['exercise_name'] =  $exe->exercise_name;
                $res['data']['quiz_time'] = $exe->time_duration;
                $res['data']['quiz_start_time'] = $quiz->start_time;
                $res['data']['quiz_end_time'] = $quiz->end_time;
                $res['data']['questions'] = $question_list;

                return response()->json($res, 200);
            }
        } else {

            $current_time = Carbon::now()->format('H:i:m');
            $end_time = Quiz::where('user_id', $user_id)->where('exercise_id', $exe_id)->value('end_time');

            if ($end_time >= $current_time) {

                $user = User::find($user_id);
                if (is_null($user)) {

                    $res['status'] = false;
                    $res['message'] = "User Not Found!";
                    $res['data'] = [];
                    return response()->json($res, 404);
                }

                $exe = Exercise::find($exe_id);
                if (is_null($exe)) {

                    $res['status'] = false;
                    $res['message'] = "Exercise Not Found!";
                    $res['data'] = [];
                    return response()->json($res, 404);
                }

                $questions = Question::where('exercise_id', $exe_id)->get();
                if (count($questions) == 0) {

                    $res['status'] = false;
                    $res['message'] = "Questions Not Found!";
                    $res['data'] = [];
                    return response()->json($res, 404);
                }

                $question_list = array();
                foreach ($questions as $ques) {

                    $ques_id = $ques->id;

                    $bookmark = Bookmark::where('user_id', $user_id)->where('exercise_id', $exe_id)->where('question_id', $ques_id)->count();

                    if ($bookmark == 1) {

                        $ques->bookmark_status = true;
                    } else {

                        $ques->bookmark_status = false;
                    }

                    array_push($question_list, $ques);
                }
                $query = Quiz::where('user_id', $user_id)->where('exercise_id', $exe_id)->value('id');
                $quiz = Quiz::find($query);

                $res['status'] = true;
                $res['message'] = "Already Start Quiz!";
                $res['data']['user_id'] = $user->id;
                $res['data']['user_name'] = $user->name;
                $res['data']['exercise_id'] =  $exe->id;
                $res['data']['exercise_name'] =  $exe->exercise_name;
                $res['data']['quiz_time'] = $exe->time_duration;
                $res['data']['quiz_start_time'] = $quiz->start_time;
                $res['data']['quiz_end_time'] = $quiz->end_time;
                $res['data']['questions'] = $question_list;

                return response()->json($res, 200);

            } else if ($end_time < $current_time) {

                $questions = Question::where('exercise_id', $exe_id)->get();
                $answers = Answer::where('exercise_id', $exe_id)->get();
                // dd($answers);
                foreach ($answers as $ans) {

                    //$ans->user_ans == ;

                }

                $res['status'] = false;
                $res['message'] = "Time Up!";
                $res['data'] = [];
                return response()->json($res, 200);
            }
        }
    }

    public function quiz_submit($user_id, $exe_id, $ques_id, $ans)
    {
        $current_time = Carbon::now()->format('H:i:m');
        $query = Quiz::where('user_id', $user_id)->where('exercise_id', $exe_id)->get();
        $end_time = Quiz::where('user_id', $user_id)->where('exercise_id', $exe_id)->value('end_time');
        $answer = Answer::where('question_id', $ques_id)->get();
        $total_questions = Question::where('exercise_id', $exe_id)->count();
        $total_answers = Answer::where('exercise_id', $exe_id)->count();
        $question = Question::where('exercise_id', $exe_id)->where('id', $ques_id)->get();

        if (count($query) == 1) {

            if ($end_time >= $current_time) {

                if ($total_questions == $total_answers) {

                    $questions = Question::where('exercise_id', $exe_id)->get();
                    $right_ans = 0;
                    $wrong_ans = 0;
                    $unans = 0;

                    foreach ($questions as $ques) {

                        // $questions = Question::where('exercise_id', $exe_id);
                        // committing;
                      
                        $answers = Answer::where('user_id', $user_id)->where('exercise_id', $exe_id)->first();
                        if(count($answer) == 1){

                            if($ques->right_ans == $answers->user_ans){

                                $res['status'] = true;
                                $res['message'] = "Question Attempt";
                                $res['data'] = [];
                                return response()->json($res, 200);
                            }
                            else{

                                $unans = $unans + 1;
                                
                            }

                            if ($answers->user_ans == $ques->right_ans) {

                                $right_ans =+1;

                            } else {

                                $wrong_ans = $wrong_ans + 1;
                            }
                        } else{
                            $res['status'] = false;
                            $res['message'] = "Answer Not Found!";
                            $res['data'] = [];
                            return response()->json($res, 404);
                        }
                    }

                    $total['Un_Attempt_Questions'] = $unans;
                    $total['total_score'] = $right_ans - $wrong_ans;
                    return response()->json($total, 200);

                } else {

                    if (count($answer) == 0) {

                        if(count($question) == 1){

                            $answer = new Answer();
                            $answer->user_id = $user_id;
                            $answer->exercise_id = $exe_id;
                            $answer->question_id = $ques_id;
                            $answer->user_ans = $ans;
                            $answer->save();

                            $res['status'] = True;
                            $res['message'] = "Your Answer Submitted Sucessfully";
                            $res['data'] = [];
                            return response()->json($res, 200);
                        }
                        else{
                            $res['status'] = false;
                            $res['message'] = "Exercise Against Question Does Not Exist!!";
                            return response()->json($res, 404);
                        }
                    } else {

                        $res['status'] = false;
                        $res['message'] = "Your Answer Already Exists!";
                        return response()->json($res, 200);
                    }
                }
            } else if ($end_time < $current_time) {

                $res['status'] = false;
                $res['message'] = "Time Up!";
                $res['data'] = [];
                return response()->json($res, 200);
            }
        } else {

            $res['status'] = false;
            $res['message'] = "Quiz Start First!";
            $res['data'] = [];
            return response()->json($res, 200);
        }
    }
    //=============================== User Login Api==========================
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:255',
            'password' => 'required|min:8',
        ];

        $validator = FacadesValidator::make($request->all(), $rules);

        if ($validator->fails()) {
            $err = $validator->errors()->getMessages();
            $msg = array_values($err)[0][0];
            $res['status'] = false;
            $res['message'] = $msg;

            return response()->json($res, 400);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {

                $res['status'] = true;
                $res['message'] = "Password Matched! You have Login successfully!";
                $res['User Data'] = User::find($user->id);
                return response()->json($res, 200);
            } else {

                $res['status'] = false;
                $res['message'] = "Password mismatch";
                return response()->json($res, 404);
            }
        } else {

            $res['status'] = false;
            $res['message'] = "User does not exist";
            return response()->json($res, 404);
        }
    }
    //=============================== User Logout Api ==========================
    public function logout()
    {
        Session::flush();
        Auth::logout();
        $res['status'] = true;
        $res['message'] = "You have been successfully logged out!";
        $res['data'] = [];
        return response()->json($res, 200);
    }
    //===============================Get Blog Category Api=============================================
    public function blog()
    {
        $blog = blog_category::get();

        if (is_null($blog)) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            $res['data'] = [];
            return response()->json($res, 404);
        } else {
            $res['status'] = true;
            $res['message'] = "Blog category List!";
            $res['data'] = $blog;
            return response()->json($res, 200);
        }
    }
    //===============================Blog posts Api=============================================
    public function blog_posts($id)
    {
        $query = DB::select('select post_title from blogs where cat_id =' . $id);
        if (is_null($query)) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            $res['data'] = [];
            return response()->json($res, 404);
        } else {
            $res['status'] = true;
            $res['message'] = "Blog Titles!";
            $res['data'] =  $query;
            return response()->json($res, 200);
        }
    }
    //===============================Show Blog posts Api Routes==========================================
    public function show_blog_post($id)
    {
        $blog_posts = Blog::find($id);
        if (is_null($blog_posts)) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            $res['data'] = [];
            return response()->json($res, 404);
        } else {
            $res['status'] = true;
            $res['message'] = "Blog";
            $res['data'] =  $blog_posts;
            return response()->json($res, 200);
        }
    }
    //================== Get All Bookmarks Api ================================
    public function bookmark(Request $request)
    {
        $user = User::find($request->user_id);
        if (is_null($user)) {

            $res['status'] = false;
            $res['message'] = "User Not Found!!";
            $res['data'] = [];
            return response()->json($res, 404);
        }

        $cat = Categoury::find($request->cat_id);
        if (is_null($cat)) {

            $res['status'] = false;
            $res['message'] = "Category Not Found!!";
            $res['data'] = [];
            return response()->json($res, 404);
        }

        $exe = Exercise::find($request->exercise_id);
        if (is_null($exe)) {

            $res['status'] = false;
            $res['message'] = "Exercise Not Found!!";
            $res['data'] = [];
            return response()->json($res, 404);
        }

        $bookmark = Bookmark::where('user_id', $request->user_id)->where('cat_id', $request->cat_id)->where('exercise_id', $request->exercise_id)->get();

        if (count($bookmark) == 0) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            $res['data'] = [];
            return response()->json($res, 404);
        } else {

            $res['status'] = true;
            $res['message'] = "Bookmark List!";
            $res['data'] =  $bookmark;
            return response()->json($res, 200);
        }
    }
    //================== Add Bookmark Api =====================================
    public function add_bookmark(Request $request)
    {
        $user = User::find($request->user_id);
        if (is_null($user)) {

            $res['status'] = false;
            $res['message'] = "User Not Found!!";
            $res['data'] = [];
            return response()->json($res, 404);
        }

        $cat = Categoury::find($request->cat_id);
        if (is_null($cat)) {

            $res['status'] = false;
            $res['message'] = "Category Not Found!!";
            $res['data'] = [];
            return response()->json($res, 404);
        }

        $exe = Exercise::find($request->exercise_id);
        if (is_null($exe)) {

            $res['status'] = false;
            $res['message'] = "Exercise Not Found!!";
            $res['data'] = [];
            return response()->json($res, 404);
        }

        $ques = Question::find($request->question_id);
        if (is_null($ques)) {

            $res['status'] = false;
            $res['message'] = "Question Not Found!!";
            $res['data'] = [];
            return response()->json($res, 404);
        }

        $bookmark = Bookmark::where('user_id', $request->user_id)->where('cat_id', $request->cat_id)->where('exercise_id', $request->exercise_id)->where('question_id', $request->question_id)->get();

        $ques = Question::where('exercise_id', $request->exercise_id)->where('id', $request->question_id)->get();
        
        if (count($bookmark) == 0) {

            if (count($ques) == 1) {

                $mark = new Bookmark();
                $mark->user_id = $request->user_id;
                $mark->cat_id = $request->cat_id;
                $mark->exercise_id = $request->exercise_id;
                $mark->question_id = $request->question_id;
                $mark->save();

                $res['status'] = True;
                $res['message'] = "Your bookmark added Sucessfully";
                $res['data'] =  $mark;
                return response()->json($res, 200);
            } else {
                $res['status'] = False;
                $res['message'] = "Question Cant Exist Against Exercise !!";
                $res['data'] =  [];
                return response()->json($res, 404);
            }
        } else {

            $res['status'] = false;
            $res['message'] = "You Have Added Already!!";
            $res['data'] = [];
            return response()->json($res, 404);
        }
    }
    //================== Delete Bookmark Api ==================================
    public function delete_bookmark($id)
    {
        $bookmark = Bookmark::find($id);
        if (is_null($bookmark)) {
            return response()->json(['message' => 'Record Not Found!'], 404);
        }
        $bookmark->delete();
        return response()->json($bookmark, 200);
    }
}
