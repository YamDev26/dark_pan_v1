<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auths.login');
});
Route::get('/inactif', function () {
    return view('pages.inactif');
})->name('page.inactif');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Route Du Super Admin
    Route::group(['middleware' => 'SuperAdmin'], function() {
        Route::group(['prefix' => 'school_year'], function() {
            Route::get('/index', [App\Http\Controllers\SuperAdminController::class, 'indexYear'])->name('school_year.index');
            Route::post('/create', [App\Http\Controllers\SuperAdminController::class, 'createYear'])->name('school_year.create');
            Route::post('/edit/{id}', [App\Http\Controllers\SuperAdminController::class, 'editYear'])->name('school_year.edit');
        });

        Route::group(['prefix' => 'cutting'], function() {
            Route::get('/index', [App\Http\Controllers\SuperAdminController::class, 'indexCutting'])->name('cutting.index');
            Route::post('/create', [App\Http\Controllers\SuperAdminController::class, 'createCutting'])->name('cutting.create');
            Route::post('/edit', [App\Http\Controllers\SuperAdminController::class, 'editCuuting'])->name('cutting.edit');
        });

        Route::group(['prefix' => 'country'], function() {
            Route::get('/index', [App\Http\Controllers\SuperAdminController::class, 'country'])->name('country.index');
            Route::post('/edit', [App\Http\Controllers\SuperAdminController::class, 'editCounty'])->name('country.edit');
        });

        Route::group(['prefix' => 'dren'], function() {
            Route::get('/index', [App\Http\Controllers\SuperAdminController::class, 'dren'])->name('dren.index');
        });

        Route::group(['prefix' => 'school'], function() {
            Route::get('/index', [App\Http\Controllers\SuperAdminController::class, 'index'])->name('school.index');
            Route::get('/create', [App\Http\Controllers\SuperAdminController::class, 'created'])->name('school.create');
            Route::post('/create', [App\Http\Controllers\SuperAdminController::class, 'stored'])->name('school.store');
            Route::get('/edit/{id}', [App\Http\Controllers\SuperAdminController::class, 'edited'])->name('school.edit');
            Route::put('/edit/{id}', [App\Http\Controllers\SuperAdminController::class, 'update'])->name('school.update');
        });
    });
    
    /**
     * Route Relative a
     */
    Route::group(['middleware' => 'UserAutres'], function() {
        Route::group(['prefix' => 'setting'], function() {
            Route::get('/index', [App\Http\Controllers\SettingController::class, 'index'])->name('setting.index');
            Route::get('/edit/{id}', [App\Http\Controllers\SettingController::class, 'edit'])->name('setting.edit');
            Route::put('/edit/{id}', [App\Http\Controllers\SettingController::class, 'update'])->name('setting.update');
        });

        Route::group(['prefix' => 'slot'], function() {
            Route::get('/index', [App\Http\Controllers\SettingController::class, 'indexSlot'])->name('slot.index');
            Route::get('/create', [App\Http\Controllers\SettingController::class, 'createSlot'])->name('slot.create');
            Route::post('/create', [App\Http\Controllers\SettingController::class, 'storeSlot'])->name('slot.store');
            Route::get('/edit', [App\Http\Controllers\SettingController::class, 'editSlot'])->name('slot.edit');
            Route::post('/edit', [App\Http\Controllers\SettingController::class, 'updateSlot'])->name('slot.update');
        });

        Route::group(['prefix' => 'level'], function() {
            Route::get('/index', [App\Http\Controllers\LevelController::class, 'index'])->name('level.index');
            Route::get('/detail/{id}', [App\Http\Controllers\LevelController::class, 'show'])->name('level.show');
            Route::get('/create/{id}', [App\Http\Controllers\LevelController::class, 'create'])->name('level.create');
            Route::post('/store/{id}', [App\Http\Controllers\LevelController::class, 'store'])->name('level.store');
        });

        Route::group(['prefix' => 'classe'], function() {
            Route::get('/index', [App\Http\Controllers\ClasseController::class, 'index'])->name('classe.index');
            Route::get('/detail/{id}', [App\Http\Controllers\ClasseController::class, 'show'])->name('classe.show');
            Route::post('/store/{id}', [App\Http\Controllers\ClasseController::class, 'store'])->name('classe.store');
            Route::get('/edit', [App\Http\Controllers\ClasseController::class, 'edit'])->name('classe.edit');
            Route::get('/list/{id}', [App\Http\Controllers\ClasseController::class, 'list'])->name('classe.list');
            Route::get('/search/{id}', [App\Http\Controllers\ClasseController::class, 'yajra'])->name('classe.yajra');
            Route::post('/edit/{id}', [App\Http\Controllers\ClasseController::class, 'update'])->name('classe.update');
            Route::post('/delete/{id}', [App\Http\Controllers\ClasseController::class, 'destroy'])->name('classe.delete');
            Route::get('/export/{id}', [App\Http\Controllers\ClasseController::class, 'export'])->name('classe.export');
            Route::post('/import', [App\Http\Controllers\ClasseController::class, 'import'])->name('classe.import');
        });

        Route::group(['prefix' => 'student'], function() {
            Route::get('/index', [App\Http\Controllers\StudentController::class, 'index'])->name('student.index');
            Route::get('/yajra', [App\Http\Controllers\StudentController::class, 'yajra'])->name('student.yajra');
            Route::get('/year/{id}', [App\Http\Controllers\StudentController::class, 'year'])->name('student.year');
            Route::get('/search/{id}', [App\Http\Controllers\StudentController::class, 'search'])->name('student.search');
            Route::get('/create', [App\Http\Controllers\StudentController::class, 'create'])->name('student.create');
            Route::post('/store', [App\Http\Controllers\StudentController::class, 'store'])->name('student.store');
            Route::get('/edit/{id}', [App\Http\Controllers\StudentController::class, 'edit'])->name('student.edit');
            Route::put('/edit/{id}', [App\Http\Controllers\StudentController::class, 'update'])->name('student.update');
            Route::get('/export', [App\Http\Controllers\StudentController::class, 'export'])->name('student.export');
            Route::post('/import', [App\Http\Controllers\StudentController::class, 'import'])->name('student.import');
        });

        Route::group(['prefix' => 'register'], function() {
            Route::get('/index', [App\Http\Controllers\RegisterController::class, 'index'])->name('register.index');
            Route::get('/create', [App\Http\Controllers\RegisterController::class, 'create'])->name('register.create');
            Route::post('/store', [App\Http\Controllers\RegisterController::class, 'store'])->name('register.store');
            Route::get('/show/{id}', [App\Http\Controllers\RegisterController::class, 'show'])->name('register.show');
            Route::get('/yajra_1', [App\Http\Controllers\RegisterController::class, 'yajra_1'])->name('register.yajra_1');
            Route::get('/yajra_1/{id}', [App\Http\Controllers\RegisterController::class, 'yajra_2'])->name('register.yajra_2');
            Route::get('/search', [App\Http\Controllers\RegisterController::class, 'search'])->name('register.search');
            Route::get('/classe', [App\Http\Controllers\RegisterController::class, 'getClasse'])->name('register.classe');
            Route::post('/delete', [App\Http\Controllers\RegisterController::class, 'destroy'])->name('register.delete');
        });

        Route::group(['prefix' => 'moyenne'], function() {
            Route::get('/index', [App\Http\Controllers\MoyenneController::class, 'index'])->name('moyenne.index');
            Route::get('/data', [App\Http\Controllers\MoyenneController::class, 'dataTable'])->name('moyenne.data');
            Route::get('/detail/{id}', [App\Http\Controllers\MoyenneController::class, 'show'])->name('moyenne.show');
            Route::get('/show/{id}', [App\Http\Controllers\MoyenneController::class, 'tableData'])->name('moyenne.result');
            Route::get('/list/{id}', [App\Http\Controllers\MoyenneController::class, 'moyenne'])->name('moyenne.list');
            Route::get('/autre/{id}', [App\Http\Controllers\MoyenneController::class, 'autres'])->name('moyenne.autre');
            Route::get('/frensh/{id}', [App\Http\Controllers\MoyenneController::class, 'frensh'])->name('moyenne.frensh');
            Route::get('/create/{id}', [App\Http\Controllers\MoyenneController::class, 'create'])->name('moyenne.create');
            Route::post('/store/{id}', [App\Http\Controllers\MoyenneController::class, 'store'])->name('moyenne.store');
            Route::get('/export/{id}', [App\Http\Controllers\MoyenneController::class, 'export'])->name('moyenne.export');
            Route::post('/import/{id}', [App\Http\Controllers\MoyenneController::class, 'import'])->name('moyenne.import');
            Route::get('/pdf/{id}', [App\Http\Controllers\MoyenneController::class, 'generate'])->name('moyenne.pdf');
            Route::get('/edit/{id}', [App\Http\Controllers\MoyenneController::class, 'nonClasse'])->name('moyenne.classe');
            Route::post('/edit/{id}', [App\Http\Controllers\MoyenneController::class, 'classeNon'])->name('moyenne.unclass');
        });

        Route::group(['prefix' => 'teacher'], function() {
            Route::get('/index', [App\Http\Controllers\TeacherController::class, 'index'])->name('teacher.index');
            Route::get('/yajra', [App\Http\Controllers\TeacherController::class, 'yajra'])->name('teacher.yajra');
            Route::get('/create', [App\Http\Controllers\TeacherController::class, 'create'])->name('teacher.create');
            Route::get('/disabled', [App\Http\Controllers\TeacherController::class, 'show'])->name('teacher.show');
            Route::get('/', [App\Http\Controllers\TeacherController::class, 'disabled'])->name('teacher.disabled');
            Route::post('/store', [App\Http\Controllers\TeacherController::class, 'store'])->name('teacher.store');
            Route::get('/edit/{id}', [App\Http\Controllers\TeacherController::class, 'edit'])->name('teacher.edit');
            Route::put('/edit/{id}', [App\Http\Controllers\TeacherController::class, 'update'])->name('teacher.update');
            Route::put('/delete/{id}', [App\Http\Controllers\TeacherController::class, 'destroy'])->name('teacher.delete');
            Route::get('/export', [App\Http\Controllers\TeacherController::class, 'export'])->name('teacher.export');
            Route::post('/import', [App\Http\Controllers\TeacherController::class, 'import'])->name('teacher.import');
        });

        Route::group(['prefix' => 'evaluated'], function() {
            Route::get('/index', [App\Http\Controllers\EvaluatedController::class, 'index'])->name('evaluated.index');
            Route::get('/yajra', [App\Http\Controllers\EvaluatedController::class, 'yajra'])->name('evaluated.yajra');
            Route::get('/matter', [App\Http\Controllers\EvaluatedController::class, 'matter'])->name('evaluated.matter');
            Route::get('/detail/{id}', [App\Http\Controllers\EvaluatedController::class, 'show'])->name('evaluated.show');
            Route::post('/store', [App\Http\Controllers\EvaluatedController::class, 'store'])->name('evaluated.store');
            Route::get('/edit', [App\Http\Controllers\EvaluatedController::class, 'edit'])->name('evaluated.edit');
            Route::put('/edit/{id}', [App\Http\Controllers\EvaluatedController::class, 'update'])->name('evaluated.update');
            Route::get('/detele/{id}', [App\Http\Controllers\EvaluatedController::class, 'destroy'])->name('evaluated.detele');
            Route::get('/edit/moyenne/{id}', [App\Http\Controllers\EvaluatedController::class, 'moyenne'])->name('evaluated.moyenne');
            Route::post('/edit/{id}', [App\Http\Controllers\EvaluatedController::class, 'moyenne_edit'])->name('evaluated.moyenne_edit');

            Route::group(['prefix' => 'note'], function() {
                Route::get('/moyenne/{id}', [App\Http\Controllers\GestionNoteController::class, 'index'])->name('note.index');
                Route::get('/frensh/{id}', [App\Http\Controllers\GestionNoteController::class, 'frensh'])->name('note.frensh');
                Route::get('/matter/{id}', [App\Http\Controllers\GestionNoteController::class, 'matter'])->name('note.matter');
                Route::get('/list/{id}', [App\Http\Controllers\GestionNoteController::class, 'show'])->name('note.show');
                Route::get('/not/{id}', [App\Http\Controllers\GestionNoteController::class, 'listNot'])->name('note.yajra');
                Route::get('/create/{id}', [App\Http\Controllers\GestionNoteController::class, 'create'])->name('note.create');
                Route::post('/store/{id}', [App\Http\Controllers\GestionNoteController::class, 'store'])->name('note.store');
                Route::get('/edit/{id}', [App\Http\Controllers\GestionNoteController::class, 'edit'])->name('note.edit');
                Route::get('/export/{id}', [App\Http\Controllers\GestionNoteController::class, 'export'])->name('note.export');
                Route::post('/import/{id}', [App\Http\Controllers\GestionNoteController::class, 'import'])->name('note.import');
            });
        });
    });
});
