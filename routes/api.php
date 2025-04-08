<?php

// use App\Http\Controllers\AuthController;
// use App\Http\Middleware\CheckUserRole;
// use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// // Route::get('/user', function (Request $request) {
// //     return $request->user();
// // })->middleware('auth:sanctum');
// // Route::middleware('auth:sanctum')->group(function () {
// //     // Route::get('/user', [AuthController::class, 'user'])->middleware(CheckUserRole::class);
// //     Route::post('/logout', [AuthController::class, 'logout']);
// // });
// Route::controller(AuthController::class)->group(function () {
//     Route::post('/register', 'register');
//     Route::post('/login', 'login');
// });
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::get('/user', [AuthController::class, 'user']);

//     // Role-Based Routes
//     Route::middleware('role:admin')->group(function () {
//         Route::get('/admin/dashboard', function () {
//             return response()->json(['message' => 'Welcome to Admin Dashboard']);
//         });
//     });

//     Route::middleware('role:assistant')->group(function () {
//         Route::get('/assistant/dashboard', function () {
//             return response()->json(['message' => 'Welcome to Assistant Dashboard']);
//         });
//     });
// });



Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'Welcome to the Admin Dashboard']);
        });
    });

    Route::middleware('role:assistant')->group(function () {
        Route::get('/assistant/dashboard', function () {
            return response()->json(['message' => 'Welcome to the Assistant Dashboard']);
        });
    });

    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', function () {
            return response()->json(['message' => 'Welcome to the User Dashboard']);
        });
    });
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::get('/users', [AuthController::class, 'users']);
    Route::post('/updaterole/{id}', [AuthController::class, 'updaterole']);
});
Route::post('/send', [MessageController::class, 'send']);
Route::get('/get', [MessageController::class, 'get']);
Route::post('/reply', [MessageController::class, 'reply']);
// Route::controller(MessageController::class)->group(function () {
//     Route::post('/send', 'send');
//     Route::get('/get', 'get');
//     Route::post('/reply' , 'reply');
// });
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
// Route::middleware('auth:sanctum')->group(function () {
    //     Route::post('/logout', [AuthController::class, 'logout']);
    //     Route::get('/user', [AuthController::class, 'user']);
    
    //     // Role-Based Routes
    //     Route::middleware('role:admin')->group(function () {
        //         Route::get('/admin/dashboard', function () {
//             return response()->json(['message' => 'Welcome to Admin Dashboard']);
//         });
//     });

//     Route::middleware('role:assistant')->group(function () {
//         Route::get('/assistant/dashboard', function () {
//             return response()->json(['message' => 'Welcome to Assistant Dashboard']);
//         });
//     });
// });
