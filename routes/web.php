<?php

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function(){
    return redirect()->route("tasks.index");
});

//To access vars outside anonymous function you have to use "use($varName)"
Route::get('/tasks', function () {

    // $tasks = \App\Models\Task::all();//هي بتجيب كل التاسكات الاول
    // $tasks = Task::latest()->get();
    $tasks = Task::latest()->paginate(10);

    return view('index', [
       "tasks" => $tasks
    ]);
})->name("tasks.index");


Route::view('/tasks/create','create')->name("Khara");

Route::get('/tasks/{task}/edit', function(Task $task){
    return view('edit', ["task" => $task]);
})->name("tasks.edit");

Route::get('/tasks/{task}', function(Task $task) {
//    $task = Task::findOrfail($id); // بتجيب 404 ما بتطلعلي ايرور اذا ما لقت السطر يعني بدل الابورت
//    $task = Task::find($id); //هاي بترجع ايرور وبتوقف السكربت كامل

//    if(!$task){
//         abort(Response::HTTP_NOT_FOUND); //This will show 404 page
//    }

   return view("show", ["task" =>  $task]);
})->name("tasks.show");

Route::post('/tasks', function(TaskRequest $request){

//Validate The Data and return it in $data variable
//    $data = $request->validated();

//    //insert Data in data base
//    $task = new Task();
//    $task->title = $data['title'];
//    $task->description = $data['description'];
//    $task->long_description = $data['long_description'];

//    //Save the model to the database.
//    $task->save();

$task = Task::create($request->validated());

   return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Task Created Successfully!');
})->name('tasks.store');



Route::put('tasks/{task}', function(Task $task, Request $request){
   //validate the data and return it in $data variable
   $data = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'long_description' => 'required',
    ]);

//    //insert Data in data base
//    $task->title = $data['title'];
//    $task->description = $data['description'];
//    $task->long_description = $data['long_description'];

//    //Save the model to the database.
//    $task->save();
    $task->update($data);

   return redirect()->route('tasks.show', ['task' =>  $task->id])->with('success', 'Task Updated Successfully!');

})->name("tasks.update");

Route::delete('/tasks/{task}', function(Task $task){

    $task->delete();

    return redirect()->route('tasks.index')->with('success', 'Task Deleted Successfully!');
})->name('tasks.destroy');

Route::patch('/tasks/{task}/toggle-completed', function(Task $task){

    //هاي عشان اعدل كولوم واحد لازم استخدم السيف ميثود وانا كريتت ميثود من باب السهوله فقط
   $task->toggleComplete();

    return redirect()->back()->with('success', 'Task Updated Successfully!');
})->name('tasks.toggle');

Route::fallback(function(){

    return redirect()->route("tasks.index");
});
