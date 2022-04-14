<?php

use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminAuthenticated;
use App\Http\Middleware\UserAuthenticated;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;
use App\Models\Categoury;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

//-----------------By Default breeze Route--------------------------
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

// USER DASHBOARD
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'user'])->name('dashboard');

// ADMIN DASHBOARD
Route::get('/admin', function () {
        $users =  User::all()->count();;
        $categoury = Categoury::all()->count();
        return view('admin.index',compact('users','categoury'));
})->middleware(['auth', 'admin'])->name('admin');

//-------------------------Admin Routes----------------------------
Route::middleware('auth','admin')->group(function () {
    
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/add_users' , [AdminController::class, 'show_user_form'])->name('userform');
    Route::post('/add_users' , [AdminController::class, 'add_users'])->name('add_users');
    Route::get('/edit_user/{id}' , [AdminController::class, 'edit_user'])->name('edit_user');
    Route::post('/update_user/{id}' , [AdminController::class, 'update_user'])->name('update_user');
    Route::get('/delete_user/{id}' , [AdminController::class, 'delete_user'])->name('delete_user');
    Route::get('/admin_logout' , [AdminController::class, 'logout'])->name('admin_logout');

    
    //===============================Categoury Routes=============================================
    Route::get('/categoury' , [AdminController::class, 'categoury'])->name('categoury');
    Route::post('/categoury' , [AdminController::class, 'add_categoury'])->name('addCategoury');
    Route::get('/categouries' , [AdminController::class, 'categouries'])->name('categouries');
    Route::get('/edit_categoury/{id}' , [AdminController::class, 'edit_categoury'])->name('edit_categoury');
    Route::PUT('/categoury_update' , [AdminController::class, 'categoury_update'])->name('categoury_update');
    Route::delete('/categoury_delete' , [AdminController::class, 'categoury_delete'])->name('categoury_delete');
    //===============================Exercise Routes=============================================
    Route::get('/exercise/{id}' , [AdminController::class, 'exercise'])->name('exercise');
    Route::post('/add_exercise/{id}' , [AdminController::class, 'add_exercise'])->name('add_exercise');
    Route::get('/edit_exercise/{id}' , [AdminController::class, 'edit_exercise'])->name('edit_exercise');
    Route::PUT('/exercise_update' , [AdminController::class, 'exercise_update'])->name('exercise_update');
    Route::delete('/exercise_delete' , [AdminController::class, 'exercise_delete'])->name('exercise_delete');
    //===============================Questions Routes=============================================
    Route::get('/questions/{id}' , [AdminController::class, 'questions'])->name('questions');
    Route::post('/add_questions/{id}' , [AdminController::class, 'add_questions'])->name('add_questions');
    Route::get('/edit_question/{id}' , [AdminController::class, 'edit_questions'])->name('edit_questions');
    Route::PUT('/question_update' , [AdminController::class, 'question_update'])->name('question_update');
    Route::delete('/question_delete' , [AdminController::class, 'question_delete'])->name('question_delete');
    //===============================Blog Routes=============================================
    Route::get('/blog' , [AdminController::class, 'blog'])->name('blog');
    Route::post('/blog' , [AdminController::class, 'add_blog'])->name('addblog');
    Route::get('/blogs' , [AdminController::class, 'blogs'])->name('blogs');
    Route::get('/edit_blog/{id}' , [AdminController::class, 'edit_blog'])->name('edit_blog');
    Route::PUT('/blog_update' , [AdminController::class, 'blog_update'])->name('blog_update');
    Route::delete('/blog_delete' , [AdminController::class, 'blog_delete'])->name('blog_delete');
    //===============================Blog posts Routes=============================================
    Route::get('/blog_posts/{id}' , [AdminController::class, 'blog_posts'])->name('blog_posts');
    Route::get('/add_blog_posts/{id}' , [AdminController::class, 'show_add_blog_posts'])->name('show_add_blog_posts');
    Route::post('/add_blog_posts/{id}' , [AdminController::class, 'add_blog_posts'])->name('add_blog_posts');
    Route::get('/edit_blog_posts/{id}' , [AdminController::class, 'edit_blog_posts'])->name('edit_blog_posts');
    Route::post('/update_blog_posts/{id}' , [AdminController::class, 'update_blog_posts'])->name('update_blog_posts');
    Route::delete('/blog_post_delete' , [AdminController::class, 'blog_post_delete'])->name('blog_post_delete');
    Route::get('/show_blog_post/{id}' , [AdminController::class, 'show_blog_post'])->name('show_blog_post');

});

require __DIR__ . '/auth.php';
