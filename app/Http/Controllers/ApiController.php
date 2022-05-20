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
use App\Models\Result;
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
            return response()->json($res, 404);
        } else {
            $res['status'] = true;
            $res['message'] = "Users List";
            $res['data'] = User::get();
            return response()->json($res);
        }
    }
    //================== Get Users by Id Api ==============================
    public function users_id($id)
    {
        $user = User::find($id);

        if (is_null($user)) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            return response()->json($res);
        } else {

            $res['status'] = true;
            $res['message'] = "Users List";
            $res['data'] = User::find($id);
            return response()->json($res);
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

            return response()->json($res);
        }
        $request['password'] = Hash::make($request['password']);
        // $request['remember_token'] = Str::random(10);
        $users = User::create($request->all());
        if (is_null($users)) {

            $res['status'] = false;
            $res['message'] = "User Can't Insert Sucessfully";
            return response()->json($res);
        } else {

            $userss = User::where('email', $request->email)->first();
            $res['status'] = true;
            $res['message'] = "User Insert Sucessfully";
            $res['data'] = $userss;
            return response()->json($res);
        }
        return response()->json($users);
    }
    //================== Update Users Api ==================================
    public function update_user($id, Request $request)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'Record Not Found!']);
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

            return response()->json($res);
        }

        $user->update($request->all());
        if (is_null($user)) {

            $res['status'] = false;
            $res['message'] = "User Can't Update Sucessfully";
            return response()->json($res);
        } else {

            $res['status'] = true;
            $res['message'] = "User Update Sucessfully";
            $res['data'] = $user;
            return response()->json($res);
        }
        return response()->json($user);
    }
    //================== Delete Users Api ==================================
    public function delete_user($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'Record Not Found!']);
        }
        $user->delete();
        return response()->json($user);
    }
    //================== Get All Category Api ================================
    public function catgory()
    {
        $category = Categoury::select('id', 'name')->get();
        if (is_null($category)) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            return response()->json($res);
        } else {
            $res['status'] = true;
            $res['message'] = "Category List!";
            $res['data'] =  $category;
            return response()->json($res);
        }
    }
    //================== Get Category by Id Api ==============================
    public function category_id($id)
    {
        $category = Categoury::find($id);
        if (is_null($category)) {
            return response()->json(['message' => 'Record Not Found!']);
        }
        return response()->json($category);
    }
    //================== Add Category Api =====================================
    public function add_category(Request $request)
    {
        $rules = [
            'name' => 'required | min:2',
        ];

        $validator = FacadesValidator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $category = Categoury::create($request->all());
        return response()->json($category);
    }
    //================== Update Category Api ==================================
    public function update_category($id, Request $request)
    {
        $category = Categoury::find($id);
        if (is_null($category)) {
            return response()->json(['message' => 'Record Not Found!']);
        }
        $rules = [
            'name' => 'required | min:2',
        ];

        $validator = FacadesValidator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $category->update($request->all());
        return response()->json($category);
    }
    //================== Delete Category Api ==================================
    public function delete_category($id)
    {
        $category = Categoury::find($id);
        if (is_null($category)) {
            return response()->json(['message' => 'Record Not Found!']);
        }
        $category->delete();
        return response()->json($category);
    }
    //=============== Get All exercises against category by id Api =================
    public function exercise(Request $request)
    {
        $cat = Categoury::find($request->cat_id);
        $exe = Exercise::where('categoury_id', $request->cat_id)->count();
        $query = Exercise::select('id', 'exercise_name', 'time_duration')->where('categoury_id', $request->cat_id)->get();

        if (count($query) == 0) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            return response()->json($res);
        } else {

            $exercise_list = array();
            $total_questions = array();

            foreach ($query as $que) {

                $quiz = Quiz::where('user_id', $request->user_id)->where('exercise_id', $que->id)->count();

                if ($quiz == 1) {

                    $que->exercise_status_solved = true;
                } else {

                    $que->exercise_status_solved = false;
                }

                array_push($exercise_list, $que);

                $ques = Question::where('exercise_id', $que->id)->count();

                if ($ques) {

                    $que->total_questions = $ques;
                } else {

                    $que->total_questions = 0;
                }

                array_push($total_questions, $que);
            }

            $res['status'] = true;
            $res['message'] = "Exercise List!";
            $res['data']['category_id'] =  $cat->id;
            $res['data']['category_name'] =  $cat->name;
            $res['data']['total_exercises'] = $exe;
            $res['data']['exercises'] = $exercise_list;

            return response()->json($res);
        }
    }
    //=============== Get All Questions against exercise by id Api ==================
    public function questions(Request $request)
    {
        $exe = Exercise::find($request->exe_id);
        if (is_null($exe)) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            return response()->json($res);
        }

        $ques = Question::where('exercise_id', $request->exe_id)->count();
        $query = DB::select('select * from questions where exercise_id =' . $request->exe_id);
        $all_questions = array();

        foreach ($query as $que) {

            $que->on_clicked = " ";

            array_push($all_questions, $que);
        }

        if (count($query) == 0) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            return response()->json($res);
        } else {

            $res['status'] = true;
            $res['message'] = "Questions List!";
            $res['data']['exercise_id'] =  $exe->id;
            $res['data']['exercise_name'] =  $exe->exercise_name;
            $res['data']['total_questions'] = $ques;
            $res['data']['questions'] = $all_questions;
            return response()->json($res);
        }
    }

    public function quiz_start(Request $request)
    {
        $query = Quiz::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->get();
        $total_questions = Question::where('exercise_id', $request->exe_id)->count();
        $total_marks = $total_questions * 4;
        $date = date('Y-m-d');
        $quiz = Quiz::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->first();

        if (count($query) == 0) {

            $user = User::find($request->user_id);
            if (is_null($user)) {

                $res['status'] = false;
                $res['message'] = "User Not Found!";
                return response()->json($res);
            }

            $exe = Exercise::find($request->exe_id);
            if (is_null($exe)) {

                $res['status'] = false;
                $res['message'] = "Exercise Not Found!";
                return response()->json($res);
            }

            $questions = Question::where('exercise_id', $request->exe_id)->get();
            if (count($questions) == 0) {

                $res['status'] = false;
                $res['message'] = "Questions Not Found Against Exercise!";
                return response()->json($res);
            }

            $current_time = Carbon::now("Asia/Karachi")->format('H:i:m');

            if ($exe->time_duration == 30) {

                $end_time = Carbon::now("Asia/Karachi")->addMinutes(30)->format('H:i:m');
            }
            if ($exe->time_duration == 60) {

                $end_time = Carbon::now("Asia/Karachi")->addMinutes(60)->format('H:i:m');
            }
            if ($exe->time_duration == 90) {

                $end_time = Carbon::now("Asia/Karachi")->addMinutes(90)->format('H:i:m');
            }
            if ($exe->time_duration == 120) {

                $end_time = Carbon::now("Asia/Karachi")->addMinutes(120)->format('H:i:m');
            }
            if ($exe->time_duration == 150) {

                $end_time = Carbon::now("Asia/Karachi")->addMinutes(150)->format('H:i:m');
            }
            if ($exe->time_duration == 180) {

                $end_time = Carbon::now("Asia/Karachi")->addMinutes(180)->format('H:i:m');
            }
            if ($exe->time_duration == 210) {

                $end_time = Carbon::now("Asia/Karachi")->addMinutes(210)->format('H:i:m');
            }
            if ($exe->time_duration == 240) {

                $end_time = Carbon::now("Asia/Karachi")->addMinutes(240)->format('H:i:m');
            }

            $quiz = new Quiz();
            $quiz->user_id = $request->user_id;
            $quiz->exercise_id = $request->exe_id;
            $quiz->start_time = $current_time;
            $quiz->end_time = $end_time;
            $quiz->save();

            if (is_null($quiz)) {

                $res['status'] = false;
                $res['message'] = "Quiz Can't created!";
                $res['data'] = [];
                return response()->json($res);
            } else {

                $question_list = array();
                $user_ans = array();
                foreach ($questions as $ques) {

                    $ques_id = $ques->id;

                    $bookmark = Bookmark::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->where('question_id', $ques_id)->count();

                    $user_answer = Answer::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->where('question_id', $ques_id)->first();

                    if ($user_answer) {

                        $ques->user_answer = $user_answer->user_ans;
                    } else {

                        $ques->user_answer = "";
                    }
                    array_push($user_ans, $ques);

                    if ($bookmark == 1) {

                        $ques->bookmark_status = true;
                        $ques->user_answer = 0;
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
                $res['data']['Total_Marks'] = $total_marks;
                $res['data']['Total_Questions'] = $total_questions;
                $res['data']['questions'] = $question_list;

                return response()->json($res);
            }
        } else {

            $current_time = Carbon::now("Asia/Karachi")->format('H:i:m');
            $end_time = Quiz::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->value('end_time');

            if ($end_time >= $current_time && $date == $quiz->created_at->format('Y-m-d')) {

                $user = User::find($request->user_id);
                if (is_null($user)) {

                    $res['status'] = false;
                    $res['message'] = "User Not Found!";
                    return response()->json($res);
                }

                $exe = Exercise::find($request->exe_id);
                if (is_null($exe)) {

                    $res['status'] = false;
                    $res['message'] = "Exercise Not Found!";
                    return response()->json($res);
                }

                $questions = Question::where('exercise_id', $request->exe_id)->get();
                if (count($questions) == 0) {

                    $res['status'] = false;
                    $res['message'] = "Questions Not Found!";
                    return response()->json($res);
                }


                $question_list = array();
                $user_ans = array();
                foreach ($questions as $ques) {

                    $ques_id = $ques->id;

                    $bookmark = Bookmark::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->where('question_id', $ques_id)->count();

                    $user_answer = Answer::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->where('question_id', $ques_id)->first();

                    if ($user_answer) {

                        $ques->user_answer = $user_answer->user_ans;
                    } else {

                        $ques->user_answer = "";
                    }
                    array_push($user_ans, $ques);

                    if ($bookmark == 1) {

                        $ques->bookmark_status = true;
                        $ques->user_answer = 0;
                    } else {

                        $ques->bookmark_status = false;
                    }
                    array_push($question_list, $ques);
                }
                $query = Quiz::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->value('id');
                $quiz = Quiz::find($query);
                $total_questions = Question::where('exercise_id', $request->exe_id)->count();
                $total_marks = $total_questions * 4;

                $res['status'] = true;
                $res['message'] = "Already Start Quiz!";
                $res['data']['user_id'] = $user->id;
                $res['data']['user_name'] = $user->name;
                $res['data']['exercise_id'] =  $exe->id;
                $res['data']['exercise_name'] =  $exe->exercise_name;
                $res['data']['quiz_time'] = $exe->time_duration;
                $res['data']['quiz_start_time'] = $quiz->start_time;
                $res['data']['quiz_end_time'] = $quiz->end_time;
                $res['data']['Total_Marks'] = $total_marks;
                $res['data']['Total_Questions'] = $total_questions;
                $res['data']['questions'] = $questions;
                return response()->json($res);
            } else if ($end_time < $current_time || $date != $quiz->created_at->format('Y-m-d')) {

                $questions = Question::where('exercise_id', $request->exe_id)->get();
                $total_questions = Question::where('exercise_id', $request->exe_id)->count();
                $total_marks = $total_questions * 4;
                $right_ans = 0;
                $wrong_ans = 0;
                $unans = 0;
                $score_right_ans = 0;

                foreach ($questions as $ques) {

                    $answers = Answer::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->where('question_id', $ques->id)->whereNotIn('user_ans', [0])->first();

                    if ($answers) {

                        if ($ques->right_ans == $answers->user_ans) {

                            $right_ans = $right_ans + 1;
                            $score_right_ans = $score_right_ans + 4;
                        } else {

                            if ($ques->right_ans != $answers->user_ans) {

                                $wrong_ans = $wrong_ans + 1;
                            }
                        }
                    } else {

                        $unans = $unans + 1;
                    }
                }
                $marks = $score_right_ans - $wrong_ans;
                $percentage = $marks / $total_marks * 100;
                if ($percentage < 0) {
                    $percentage = 0;
                } else {
                    $percentage;
                }
                $res['status'] = false;
                $res['message'] = "Time Up!";
                // $res['data']['Total_Marks'] = $total_marks;
                // $res['data']['Total_Questions'] = $total_questions;
                // $res['data']['Un_Attempt_Questions'] = $unans;
                // $res['data']['Correct_Answers'] = $right_ans;
                // $res['data']['Wrong_Answers'] = $wrong_ans;
                // $res['data']['total_score'] = $score_right_ans - $wrong_ans;
                // $res['data']['Percentage'] = $percentage . "%";
                return response()->json($res);
            }
        }
    }

    public function quiz_submit(Request $request)
    {
        $current_time = Carbon::now("Asia/Karachi")->format('H:i:m');
        $query = Quiz::where('user_id',  $request->user_id)->where('exercise_id', $request->exe_id)->get();
        $end_time = Quiz::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->value('end_time');
        $quiz = Quiz::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->first();
        $answer = Answer::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->where('question_id', $request->ques_id)->get();
        $answer_object = Answer::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->where('question_id', $request->ques_id)->first();
        $total_questions = Question::where('exercise_id', $request->exe_id)->count();
        $total_answers = Answer::where('user_id',  $request->user_id)->where('exercise_id', $request->exe_id)->count();
        $question = Question::where('exercise_id', $request->exe_id)->where('id', $request->ques_id)->get();
        $date = date('Y-m-d');

        if (count($query) == 1) {

            if ($end_time >= $current_time && $date == $quiz->created_at->format('Y-m-d')) {

                if ($total_questions == $total_answers) {

                    $questions = Question::where('exercise_id', $request->exe_id)->get();
                    $total_questions = Question::where('exercise_id', $request->exe_id)->count();
                    $total_marks = $total_questions * 4;
                    $right_ans = 0;
                    $wrong_ans = 0;
                    $unans = 0;
                    $score_right_ans = 0;

                    foreach ($questions as $ques) {

                        $answers = Answer::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->where('question_id', $ques->id)->whereNotIn('user_ans', [0])->first();

                        if ($answers) {

                            if ($ques->right_ans == $answers->user_ans) {

                                $right_ans = $right_ans + 1;
                                $score_right_ans = $score_right_ans + 4;
                            } else {

                                if ($ques->right_ans != $answers->user_ans) {

                                    $wrong_ans = $wrong_ans + 1;
                                }
                            }
                        } else {

                            $unans = $unans + 1;
                        }
                    }
                    $marks = $score_right_ans - $wrong_ans;
                    $percentage = $marks / $total_marks * 100;
                    if ($percentage < 0) {
                        $percentage = 0;
                    } else {
                        $percentage;
                    }

                    //--------------------------------percntile_find-----------------------------------------------
                    $exe = Exercise::find($request->exe_id);
                    $total_users =  Result::where('cat_id', $exe->categoury_id)->where('exercise_id',  $request->exe_id)->count();
                    $users_results =  Result::where('cat_id', $exe->categoury_id)->where('exercise_id',  $request->exe_id)->orderBy('user_score', 'DESC')->get();
                    $index = 0;
                    $user_index = 0;
                    foreach ($users_results as $results) {

                        if ($results->user_id  ==  $request->user_id && $results->cat_id ==  $exe->categoury_id && $results->exercise_id == $request->exe_id) {

                            $user_index = $index;
                        }
                        $index++;
                    }

                    $pos = $total_users - $user_index;

                    $percentile = $pos /  $total_users * 100;

                    $result = Result::where('user_id', $request->user_id)->where('exercise_id',  $request->exe_id)->get();
                    if (count($result) > 0) {

                        $res['status'] = true;
                        $res['message'] = "Result Already  Exists";
                        $res['data']['Total_Marks'] = $total_marks;
                        $res['data']['Total_Questions'] = $total_questions;
                        $res['data']['Un_Attempt_Questions'] = $unans;
                        $res['data']['Correct_Answers'] = $right_ans;
                        $res['data']['Wrong_Answers'] = $wrong_ans;
                        $res['data']['total_score'] = $score_right_ans - $wrong_ans;
                        $res['data']['Percentage'] = $percentage . "%";
                        $res['data']['Percentile'] = $percentile . "%";
                        $res['data']['Total_users_in_test_Appeared'] = $total_users;
                        return response()->json($res);
                    } else {


                        $exe = Exercise::find($request->exe_id)->first();

                        $result = new Result();
                        $result->user_id = $request->user_id;
                        $result->cat_id = $exe->categoury_id;
                        $result->exercise_id = $request->exe_id;
                        $result->total_marks =  $total_marks;
                        $result->right_ans =   $right_ans;
                        $result->wrong_ans = $wrong_ans;
                        $result->un_ans = $unans;
                        $result->user_score = $marks;
                        $result->percentage =  $percentage;
                        $result->save();

                        //--------------------------------percntile_find-----------------------------------------------
                        $exe = Exercise::find($request->exe_id);
                        $total_users =  Result::where('cat_id', $exe->categoury_id)->where('exercise_id',  $request->exe_id)->count();
                        $users_results =  Result::where('cat_id', $exe->categoury_id)->where('exercise_id',  $request->exe_id)->orderBy('user_score', 'DESC')->get();
                        $index = 0;
                        $user_index = 0;
                        foreach ($users_results as $results) {

                            if ($results->user_id  ==  $request->user_id && $results->cat_id ==  $exe->categoury_id && $results->exercise_id == $request->exe_id) {

                                $user_index = $index;
                            }
                            $index++;
                        }

                        $pos = $total_users - $user_index;

                        $percentile = $pos /  $total_users * 100;


                        $res['status'] = true;
                        $res['message'] = "Final Result!!";
                        $res['data']['Total_Marks'] = $total_marks;
                        $res['data']['Total_Questions'] = $total_questions;
                        $res['data']['Un_Attempt_Questions'] = $unans;
                        $res['data']['Correct_Answers'] = $right_ans;
                        $res['data']['Wrong_Answers'] = $wrong_ans;
                        $res['data']['total_score'] = $score_right_ans - $wrong_ans;
                        $res['data']['Percentage'] = $percentage . "%";
                        $res['data']['Percentile'] = $percentile . "%";
                        $res['data']['Total_users_in_test_Appeared'] = $total_users;
                        return response()->json($res);
                    }
                } else {

                    if (count($answer) == 0) {

                        if (count($question) == 1) {

                            $answer = new Answer();
                            $answer->user_id = $request->user_id;
                            $answer->exercise_id = $request->exe_id;
                            $answer->question_id = $request->ques_id;
                            $answer->user_ans = $request->user_ans;
                            $answer->save();

                            $res['status'] = True;
                            $res['message'] = "Your Answer Submitted Sucessfully";
                            $res['data'] = $answer;
                            return response()->json($res);
                        } else {
                            $res['status'] = false;
                            $res['message'] = "Exercise Against Question Does Not Exist!!";
                            return response()->json($res);
                        }
                    } else {

                        $res['status'] = false;
                        $res['message'] = "Your Answer Already Exists!";
                        $res['data'] = $answer_object;
                        return response()->json($res);
                    }
                }
            } else if ($end_time < $current_time || $date != $quiz->created_at->format('Y-m-d')) {

                $questions = Question::where('exercise_id', $request->exe_id)->get();
                $total_questions = Question::where('exercise_id', $request->exe_id)->count();
                $total_marks = $total_questions * 4;
                $right_ans = 0;
                $wrong_ans = 0;
                $unans = 0;
                $score_right_ans = 0;

                foreach ($questions as $ques) {

                    $answers = Answer::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->where('question_id', $ques->id)->whereNotIn('user_ans', [0])->first();

                    if ($answers) {

                        if ($ques->right_ans == $answers->user_ans) {

                            $right_ans = $right_ans + 1;
                            $score_right_ans = $score_right_ans + 4;
                        } else {

                            if ($ques->right_ans != $answers->user_ans) {

                                $wrong_ans = $wrong_ans + 1;
                            }
                        }
                    } else {

                        $unans = $unans + 1;
                    }
                }
                $marks = $score_right_ans - $wrong_ans;
                $percentage = $marks / $total_marks * 100;
                if ($percentage < 0) {
                    $percentage = 0;
                } else {
                    $percentage;
                }

                //--------------------------------percntile_find-----------------------------------------------
                $exe = Exercise::find($request->exe_id);
                $total_users =  Result::where('cat_id', $exe->categoury_id)->where('exercise_id',  $request->exe_id)->count();
                $users_results =  Result::where('cat_id', $exe->categoury_id)->where('exercise_id',  $request->exe_id)->orderBy('user_score', 'DESC')->get();
                $index = 0;
                $user_index = 0;
                foreach ($users_results as $results) {

                    if ($results->user_id  ==  $request->user_id && $results->cat_id ==  $exe->categoury_id && $results->exercise_id == $request->exe_id) {

                        $user_index = $index;
                    }
                    $index++;
                }

                $pos = $total_users - $user_index;

                $percentile = $pos /  $total_users * 100;

                $result = Result::where('user_id', $request->user_id)->where('exercise_id',  $request->exe_id)->get();
                if (count($result) > 0) {

                    $res['status'] = true;
                    $res['message'] = "Time Up See Results!!";
                    $res['data']['Total_Marks'] = $total_marks;
                    $res['data']['Total_Questions'] = $total_questions;
                    $res['data']['Un_Attempt_Questions'] = $unans;
                    $res['data']['Correct_Answers'] = $right_ans;
                    $res['data']['Wrong_Answers'] = $wrong_ans;
                    $res['data']['total_score'] = $score_right_ans - $wrong_ans;
                    $res['data']['Percentage'] = $percentage . "%";
                    $res['data']['Percentile'] = $percentile . "%";
                    $res['data']['Total_users_in_test_Appeared'] = $total_users;
                    return response()->json($res);
                } else {


                    $exe = Exercise::find($request->exe_id)->first();

                    $result = new Result();
                    $result->user_id = $request->user_id;
                    $result->cat_id = $exe->categoury_id;
                    $result->exercise_id = $request->exe_id;
                    $result->total_marks =  $total_marks;
                    $result->right_ans =   $right_ans;
                    $result->wrong_ans = $wrong_ans;
                    $result->un_ans = $unans;
                    $result->user_score = $marks;
                    $result->percentage =  $percentage;
                    $result->save();

                    //--------------------------------percntile_find-----------------------------------------------
                    $exe = Exercise::find($request->exe_id);
                    $total_users =  Result::where('cat_id', $exe->categoury_id)->where('exercise_id',  $request->exe_id)->count();
                    $users_results =  Result::where('cat_id', $exe->categoury_id)->where('exercise_id',  $request->exe_id)->orderBy('user_score', 'DESC')->get();
                    $index = 0;
                    $user_index = 0;
                    foreach ($users_results as $results) {

                        if ($results->user_id  ==  $request->user_id && $results->cat_id ==  $exe->categoury_id && $results->exercise_id == $request->exe_id) {

                            $user_index = $index;
                        }
                        $index++;
                    }

                    $pos = $total_users - $user_index;

                    $percentile = $pos /  $total_users * 100;



                    $res['status'] = true;
                    $res['message'] = "Final Result!!";
                    $res['data']['Total_Marks'] = $total_marks;
                    $res['data']['Total_Questions'] = $total_questions;
                    $res['data']['Un_Attempt_Questions'] = $unans;
                    $res['data']['Correct_Answers'] = $right_ans;
                    $res['data']['Wrong_Answers'] = $wrong_ans;
                    $res['data']['total_score'] = $score_right_ans - $wrong_ans;
                    $res['data']['Percentage'] = $percentage . "%";
                    $res['data']['Percentile'] = $percentile . "%";
                    $res['data']['Total_users_in_test_Appeared'] = $total_users;
                    return response()->json($res);
                }
            }
        } else {

            $res['status'] = false;
            $res['message'] = "Quiz Start First!";
            return response()->json($res);
        }
    }
    //====================================Result Api ======================================
    public function result(Request $request)
    {
        $user = User::find($request->user_id);
        if (is_null($user)) {

            $res['status'] = false;
            $res['message'] = "User Not Found!";
            return response()->json($res);
        }

        $exe = Exercise::find($request->exe_id);
        if (is_null($exe)) {

            $res['status'] = false;
            $res['message'] = "Exercise Not Found!";
            return response()->json($res);
        }
        $questions = Question::where('exercise_id', $request->exe_id)->get();
        if (count($questions) == 0) {

            $res['status'] = false;
            $res['message'] = "Questions Not Found!";
            return response()->json($res);
        }
        $quiz = Quiz::where('user_id',  $request->user_id)->where('exercise_id', $request->exe_id)->first();
        if ($quiz) {

            $quiz->status = 1;
            $quiz->save();
        } else {
            $res['status'] = false;
            $res['message'] = "Quiz Start First!";
            return response()->json($res);
        }

        $questions = Question::where('exercise_id', $request->exe_id)->get();
        $total_questions = Question::where('exercise_id', $request->exe_id)->count();
        $total_marks = $total_questions * 4;
        $right_ans = 0;
        $wrong_ans = 0;
        $unans = 0;
        $score_right_ans = 0;

        foreach ($questions as $ques) {

            $answers = Answer::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->where('question_id', $ques->id)->whereNotIn('user_ans', [0])->first();



            if ($answers) {

                if ($ques->right_ans == $answers->user_ans) {

                    $right_ans = $right_ans + 1;
                    $score_right_ans = $score_right_ans + 4;
                } else {

                    if ($ques->right_ans != $answers->user_ans) {

                        $wrong_ans = $wrong_ans + 1;
                    }
                }
            } else {

                $unans = $unans + 1;
            }
        }
        $marks = $score_right_ans - $wrong_ans;
        $percentage = $marks / $total_marks * 100;
        if ($percentage < 0) {
            $percentage = 0;
        } else {
            $percentage;
        }
        //--------------------------------percntile_find-----------------------------------------------
        $exe = Exercise::find($request->exe_id);
        $total_users =  Result::where('cat_id', $exe->categoury_id)->where('exercise_id',  $request->exe_id)->count();
        $users_results =  Result::where('cat_id', $exe->categoury_id)->where('exercise_id',  $request->exe_id)->orderBy('user_score', 'DESC')->get();
        $index = 0;
        $user_index = 0;
        foreach ($users_results as $results) {

            if ($results->user_id  ==  $request->user_id && $results->cat_id ==  $exe->categoury_id && $results->exercise_id == $request->exe_id) {

                $user_index = $index;
            }
            $index++;
        }

        $pos = $total_users - $user_index;

        $percentile = $pos /  $total_users * 100;

        $result = Result::where('user_id', $request->user_id)->where('exercise_id',  $request->exe_id)->get();
        if (count($result) > 0) {

            $res['status'] = true;
            $res['message'] = "Result Already Exists";
            $res['data']['Total_Marks'] = $total_marks;
            $res['data']['Total_Questions'] = $total_questions;
            $res['data']['Un_Attempt_Questions'] = $unans;
            $res['data']['Correct_Answers'] = $right_ans;
            $res['data']['Wrong_Answers'] = $wrong_ans;
            $res['data']['total_score'] = $score_right_ans - $wrong_ans;
            $res['data']['Percentage'] = $percentage . "%";
            $res['data']['Percentile'] = $percentile . "%";
            $res['data']['Total_users_in_test_Appeared'] = $total_users;
            // $res['data']['Total_users_in_test_list'] = $users_results;

            return response()->json($res);
        } else {


            $exe = Exercise::find($request->exe_id);
            $result = new Result();
            $result->user_id = $request->user_id;
            $result->cat_id = $exe->categoury_id;
            $result->exercise_id = $request->exe_id;
            $result->total_marks =  $total_marks;
            $result->right_ans =   $right_ans;
            $result->wrong_ans = $wrong_ans;
            $result->un_ans = $unans;
            $result->user_score = $marks;
            $result->percentage =  $percentage;
            $result->save();

            //--------------------------------percntile_find-----------------------------------------------
            $exe = Exercise::find($request->exe_id);
            $total_users =  Result::where('cat_id', $exe->categoury_id)->where('exercise_id',  $request->exe_id)->count();
            $users_results =  Result::where('cat_id', $exe->categoury_id)->where('exercise_id',  $request->exe_id)->orderBy('user_score', 'DESC')->get();
            $index = 0;
            $user_index = 0;
            foreach ($users_results as $results) {

                if ($results->user_id  ==  $request->user_id && $results->cat_id ==  $exe->categoury_id && $results->exercise_id == $request->exe_id) {

                    $user_index = $index;
                }
                $index++;
            }

            $pos = $total_users - $user_index;

            $percentile = $pos /  $total_users * 100;


            $res['status'] = true;
            $res['message'] = "Final Result!!";
            $res['data']['Total_Marks'] = $total_marks;
            $res['data']['Total_Questions'] = $total_questions;
            $res['data']['Un_Attempt_Questions'] = $unans;
            $res['data']['Correct_Answers'] = $right_ans;
            $res['data']['Wrong_Answers'] = $wrong_ans;
            $res['data']['total_score'] = $score_right_ans - $wrong_ans;
            $res['data']['Percentage'] = $percentage . "%";
            $res['data']['Percentile'] = $percentile . "%";
            $res['data']['Total_users_in_test_Appeared'] = $total_users;
            return response()->json($res);
        }
    }
    //====================================Pending Quiz By User Api ===================================
    public function pending(Request $request)
    {
        $user = User::find($request->user_id);
        if (is_null($user)) {

            $res['status'] = false;
            $res['message'] = "User Not Found!";
            return response()->json($res);
        }

        $query = Quiz::where('user_id',  $request->user_id)->whereNotIn('status', [1])->get();
        $current_time = Carbon::now("Asia/Karachi")->format('H:i:m');
        $date = date('Y-m-d');

        $pending_list = array();
        $exe_id = array();
        $cat_id = array();

        if (count($query) > 0) {

            foreach ($query as $que) {

                if ($current_time <= $que->end_time && $date == $que->created_at->format('Y-m-d')) {

                    $que->time_remaining  = \Carbon\Carbon::parse($que->end_time)->diffInMinutes(\Carbon\Carbon::parse($current_time));
                } else {

                    $que->time_remaining  = 0;
                }
                array_push($pending_list, $que);

                $exe = Exercise::where('id', $que->exercise_id)->first();

                if ($exe) {

                    $que->category_id = $exe->categoury_id;
                }
                array_push($cat_id, $que);
            }
            if ($pending_list) {

                $que->exercise_id = $que->exercise_id;
                array_push($exe_id, $que);

                $res['status'] = True;
                $res['message'] = "Pending Quiz List Against User!!";
                $res['data'] = $pending_list;
                return response()->json($res);
            } else {

                $res['status'] = false;
                $res['message'] = "No Quiz Available!!";
                return response()->json($res);
            }
        } else {
            $res['status'] = false;
            $res['message'] = "No Quiz Available!!";
            return response()->json($res);
        }
    }

    //==============================Review Api ============================================

    public function review(Request $request)
    {

        $questions = Question::where('exercise_id', $request->exe_id)->get();
        if (count($questions) == 0) {

            $res['status'] = false;
            $res['message'] = "Questions Not Found Against Exercise!";
            return response()->json($res);
        }
        $user_ans = array();
        foreach ($questions as $ques) {

            $answers = Answer::where('user_id', $request->user_id)->where('exercise_id', $request->exe_id)->where('question_id', $ques->id)->first();

            if ($answers) {
                $ques->user_ans = $answers->user_ans;
            } else {
                $ques->user_ans = "";
            }
            array_push($user_ans, $ques);
        }

        $res['status'] = true;
        $res['message'] = "Question Review!!";
        $res['data']['question_list'] = $questions;
        return response()->json($res);
    }

    //=============================== User Login Api==========================
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:255',
            'password' => 'required',
        ];

        $validator = FacadesValidator::make($request->all(), $rules);

        if ($validator->fails()) {
            $err = $validator->errors()->getMessages();
            $msg = array_values($err)[0][0];
            $res['status'] = false;
            $res['message'] = $msg;

            return response()->json($res);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {

                $res['status'] = true;
                $res['message'] = "Password Matched! You have Login successfully!";
                $res['User Data'] = User::find($user->id);
                return response()->json($res);
            } else {

                $res['status'] = false;
                $res['message'] = "Password mismatch";
                return response()->json($res);
            }
        } else {

            $res['status'] = false;
            $res['message'] = "User does not exist";
            return response()->json($res);
        }
    }

    //=============================== otp check User Api =============================

    public function otp_check(Request $request)
    {

        $users = User::where('phone', $request->phone)->first();
        if ($users) {
            $res['status'] = true;
            $res['message'] = "users";
            $res['User Data'] = $users;
            return response()->json($res);
        } else {
            $res['status'] = false;
            $res['message'] = "User does not exist";
            return response()->json($res);
        }
    }

    //=============================== otp login User Api ===========================

    public function otp_login(Request $request)
    {


        $user = User::where('phone',  $request->phone)->get();

        if (count($user) == 0) {

            $user_email = User::where('email',  $request->email)->get();

            if (count($user_email) == 1) {
                $res['status'] = false;
                $res['message'] = "Email Already exists";
                return response()->json($res);
            } else {

                $users = new User();
                $users->name = $request->name;
                $users->email = $request->email;
                $users->phone = $request->phone;
                $users->password =  Hash::make($request->phone);
                $users->save();

                $userss = User::where('phone',  $request->phone)->first();

                $res['status'] = true;
                $res['message'] = "user";
                $res['User Data'] = $userss;
                return response()->json($res);
            }
        } else {
            $res['status'] = false;
            $res['message'] = "User Already exists";
            return response()->json($res);
        }
    }

    //=============================== gmail check User Api =============================

    public function email_check(Request $request)
    {

        $users = User::where('user_id', $request->user_id)->first();
        if ($users) {
            $res['status'] = true;
            $res['message'] = "users";
            $res['User Data'] = $users;
            return response()->json($res);
        } else {
            $res['status'] = false;
            $res['message'] = "User does not exist";
            return response()->json($res);
        }
    }

    //=============================== email login User Api ===========================

    public function email_login(Request $request)
    {


        $user = User::where('user_id',  $request->user_id)->get();

        if (count($user) == 0) {

            $user_email = User::where('email',  $request->email)->get();

            if (count($user_email) == 1) {
                $res['status'] = false;
                $res['message'] = "Email Already exists";
                return response()->json($res);
            } else {

                $users = new User();
                $users->name = $request->name;
                $users->email = $request->email;
                $users->user_id = $request->user_id;
                $users->password =  Hash::make($request->email);
                $users->save();

                $userss = User::where('user_id',  $request->user_id)->first();

                $res['status'] = true;
                $res['message'] = "user";
                $res['User Data'] = $userss;
                return response()->json($res);
            }
        } else {
            $res['status'] = false;
            $res['message'] = "User Already exists";
            return response()->json($res);
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
        return response()->json($res);
    }
    //===============================Get Blog Category Api=============================================
    public function blog()
    {
        $blog = blog_category::get();

        if (count($blog) == 0) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            return response()->json($res);
        } else {
            $res['status'] = true;
            $res['message'] = "Blog category List!";
            $res['data'] = $blog;
            return response()->json($res);
        }
    }
    //===============================Blog posts Api=============================================
    public function blog_posts(Request $request)
    {

        $query = Blog::select('post_title', 'id')->where('cat_id', $request->cat_id)->get();
        if (count($query) == 0) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            return response()->json($res);
        } else {

            $res['status'] = true;
            $res['message'] = "Blog Titles!";
            $res['data'] =  $query;
            return response()->json($res);
        }
    }
    //===============================Show Blog posts Api Routes==========================================
    public function show_blog_post(Request $request)
    {

        $blog_posts_list = Blog::where('cat_id', $request->cat_id)->whereNotIn('id', [$request->blog_id])->get();
        if (count($blog_posts_list) == 0) {

            $res['status'] = false;
            $res['message'] = "Blog List Not Found!";
            return response()->json($res);
        }
        $blog_posts = Blog::where('cat_id', $request->cat_id)->where('id', $request->blog_id)->first();

        if (is_null($blog_posts)) {

            $res['status'] = false;
            $res['message'] = "Blog Against category Not Found!";
            return response()->json($res);
        } else {

            $res['status'] = true;
            $res['message'] = "Blog";
            $res['Blog'] =  $blog_posts;
            $res['data'] = $blog_posts_list;
            return response()->json($res);
        }
    }
    //=========================== Get All Bookmarks Api ===================================================
    public function bookmark(Request $request)
    {
        $user = User::find($request->user_id);
        if (is_null($user)) {

            $res['status'] = false;
            $res['message'] = "User Not Found!!";
            return response()->json($res);
        }

        $cat = Categoury::find($request->cat_id);
        if (is_null($cat)) {

            $res['status'] = false;
            $res['message'] = "Category Not Found!!";
            return response()->json($res);
        }

        $exe = Exercise::find($request->exercise_id);
        if (is_null($exe)) {

            $res['status'] = false;
            $res['message'] = "Exercise Not Found!!";
            $res['data'] = [];
            return response()->json($res);
        }

        $bookmark = Bookmark::where('user_id', $request->user_id)->where('cat_id', $request->cat_id)->where('exercise_id', $request->exercise_id)->get();



        if (count($bookmark) == 0) {

            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            return response()->json($res);
        } else {

            $res['status'] = true;
            $res['message'] = "Bookmark List!";
            $res['data'] =  $bookmark;
            return response()->json($res);
        }
    }
    //=============================== Add Bookmark Api ===================================================
    public function add_bookmark(Request $request)
    {
        $user = User::find($request->user_id);
        if (is_null($user)) {

            $res['status'] = false;
            $res['message'] = "User Not Found!!";
            return response()->json($res);
        }

        $cat = Categoury::find($request->cat_id);
        if (is_null($cat)) {

            $res['status'] = false;
            $res['message'] = "Category Not Found!!";
            return response()->json($res);
        }

        $exe = Exercise::find($request->exercise_id);
        if (is_null($exe)) {

            $res['status'] = false;
            $res['message'] = "Exercise Not Found!!";
            return response()->json($res);
        }

        $ques = Question::find($request->question_id);
        if (is_null($ques)) {

            $res['status'] = false;
            $res['message'] = "Question Not Found!!";
            return response()->json($res);
        }

        $bookmark = Bookmark::where('user_id', $request->user_id)->where('cat_id', $request->cat_id)->where('exercise_id', $request->exercise_id)->where('question_id', $request->question_id)->get();

        $ques = Question::where('exercise_id', $request->exercise_id)->where('id', $request->question_id)->get();

        $answer = Answer::where('user_id', $request->user_id)->where('exercise_id', $request->exercise_id)->where('question_id', $request->question_id)->get();

        if (count($bookmark) == 0) {

            if (count($ques) == 1) {

                if (count($answer) == 0) {

                    $mark = new Bookmark();
                    $mark->user_id = $request->user_id;
                    $mark->cat_id = $request->cat_id;
                    $mark->exercise_id = $request->exercise_id;
                    $mark->question_id = $request->question_id;
                    $mark->save();

                    $res['status'] = True;
                    $res['message'] = "Your bookmark added Sucessfully";
                    $res['data'] =  $mark;
                    return response()->json($res);
                } else {
                    $res['status'] = False;
                    $res['message'] = "You have Solved This Question!!";
                    return response()->json($res);
                }
            } else {
                $res['status'] = False;
                $res['message'] = "Question Cant Exist Against Exercise !!";
                return response()->json($res);
            }
        } else {

            $res['status'] = false;
            $res['message'] = "You Have Added Already!!";
            return response()->json($res);
        }
    }
    //================== Delete Bookmark Api ==================================
    public function delete_bookmark(Request $request)
    {
        $bookmark = Bookmark::where('user_id', $request->user_id)->where('cat_id', $request->cat_id)->where('exercise_id', $request->exercise_id)->where('question_id', $request->question_id)->first();
        if (is_null($bookmark)) {
            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            return response()->json([$res]);
        } else {
            $bookmark->delete();
            $res['status'] = True;
            $res['message'] = "You have removed bookmark Sucessfully";
            return response()->json($res);
        }
    }

    public function search_category(Request $request)
    {

        $result = Categoury::where('name', 'LIKE', "%{$request->cat_name}%")->get();

        if (count($result) == 0) {
            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            return response()->json([$res]);
        } else {
            $res['status'] = True;
            $res['message'] = "Search List!!";
            $res['data'] = $result;
            return response()->json($res);
        }
    }

    public function search_exercise(Request $request)
    {

        $result = Exercise::where('exercise_name', 'LIKE', "%{$request->exe_name}%")->get();

        if (count($result) == 0) {
            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            return response()->json([$res]);
        } else {
            $res['status'] = True;
            $res['message'] = "Search List!!";
            $res['data'] = $result;
            return response()->json($res);
        }
    }

    public function search_blog(Request $request)
    {

        $result = blog_category::where('blog_name', 'LIKE', "%{$request->blog_name}%")->get();

        if (count($result) == 0) {
            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            return response()->json([$res]);
        } else {
            $res['status'] = True;
            $res['message'] = "Search List!!";
            $res['data'] = $result;
            return response()->json($res);
        }
    }

    public function search_blog_title(Request $request)
    {

        $result = Blog::where('post_title', 'LIKE', "%{$request->post_title}%")->get();

        if (count($result) == 0) {
            $res['status'] = false;
            $res['message'] = "Record Not Found!";
            return response()->json([$res]);
        } else {
            $res['status'] = True;
            $res['message'] = "Search List!!";
            $res['data'] = $result;
            return response()->json($res);
        }
    }
}
