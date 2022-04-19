<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiController;
use App\Http\Middleware\AdminAuthenticated;
use App\Http\Middleware\UserAuthenticated;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;
use App\Models\Categoury;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//================== Get All Users Api ================================
Route::get('/users', [ApiController::class, 'users']);
//================== Get Users by Id Api ==============================
Route::get('/search_user/{id}', [ApiController::class, 'users_id']);
//================== Add Users Api ====================================
Route::post('/add_users' , [ApiController::class, 'add_users']);
//================== Update Users Api ==================================
Route::post('/update_user/{id}' , [ApiController::class, 'update_user']);
//================== Delete Users Api ==================================
Route::get('/delete_user/{id}' , [ApiController::class, 'delete_user']);

//================== Get All Category Api ================================
Route::get('/category', [ApiController::class, 'catgory']);
//================== Get Category by Id Api ==============================
Route::get('/category/{id}', [ApiController::class, 'category_id']);
//================== Add Category Api =====================================
Route::post('/add_category', [ApiController::class, 'add_category']);
//================== Update Category Api ==================================
Route::post('/update_category/{id}', [ApiController::class, 'update_category']);
//================== Delete Category Api ==================================
Route::get('/delete_category/{id}' , [ApiController::class, 'delete_category']);

//=============== Get All exercises against category by id Api =================
Route::get('/exercise' , [ApiController::class, 'exercise']);
//=============== Get All Questions against exercise by id Api =================
Route::get('/questions' , [ApiController::class, 'questions']);
//=============== Get Quiz  Api =============================
Route::post('/quiz_start' , [ApiController::class, 'quiz_start']);
Route::post('/quiz' , [ApiController::class, 'quiz_submit']);
Route::post('/result' , [ApiController::class, 'result']);

//=============================== User Login ==========================
Route::post('/login',  [ApiController::class, 'login']);
//=============================== User Logout ==========================
Route::get('/logout',  [ApiController::class, 'logout']);

//===============================Get Blog Category Api Route=============================================
Route::get('/blog' , [ApiController::class, 'blog'])->name('blog');

//===============================Blog posts Api Routes=============================================
Route::get('/blog_posts' , [ApiController::class, 'blog_posts'])->name('blog_posts');
//===============================Show Blog posts Api Routes==========================================
Route::get('/show_blog_post' , [ApiController::class, 'show_blog_post'])->name('show_blog_post');

//================== Get All Bookmarks Api ================================
Route::get('/bookmark', [ApiController::class, 'bookmark']);
//================== Add Bookmark Api =====================================
Route::post('/add_bookmark', [ApiController::class, 'add_bookmark']);
//================== Delete Bookmark Api ==================================
Route::get('/delete_bookmark' , [ApiController::class, 'delete_bookmark']);



