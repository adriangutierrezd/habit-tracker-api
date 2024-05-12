<?php

use App\Constants;
use App\Http\Controllers\V1\HabitRecordsController;
use App\Http\Controllers\V1\HabitsController;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'v1',
], function () {

    Route::post('auth', function (Request $request) {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $basicToken = $user->createToken('basic-token');

            return [
                'token' => $basicToken->plainTextToken,
                'user' => $user
            ];
        }
    });

    Route::post('sign-up', function (Request $request) {

        try {
            $user = new User([
                'name' => $request->email,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $user->save();

            $credentials = [
                'email' => $request->email,
                'password' => $request->password
            ];
            Auth::attempt($credentials);
            $user = Auth::user();

            $basicToken = $user->createToken('basic-token');

            return [
                'data' => [
                    'user' => $user,
                    'token' => $basicToken->plainTextToken
                ],
                'status' => Constants::HTTP_CREATED_CODE,
                'message' => Constants::HTTP_CREATED_MSG
            ];
        } catch (QueryException $e) {
            if ($e->errorInfo && $e->errorInfo[1] == 1062) {
                return [
                    'data' => [],
                    'status' => Constants::HTTP_BAD_REQUEST_CODE,
                    'message' => 'El email ya está en uso'
                ];
            } else {
                return [
                    'data' => [],
                    'status' => Constants::HTTP_SERVER_ERROR_CODE,
                    'message' => Constants::HTTP_SERVER_ERROR_MSG
                ];
            }
        } catch (Exception $e) {
            return [
                'data' => [],
                'status' => Constants::HTTP_SERVER_ERROR_CODE,
                'message' => Constants::HTTP_SERVER_ERROR_MSG
            ];
        }
    });
});


Route::group([
    'prefix' => 'v1',
    'namespace' => 'App\Http\Controllers\V1',
    'middleware' => 'auth:sanctum'
], function () {
    Route::apiResource('habits', HabitsController::class);
    Route::apiResource('habit-records', HabitRecordsController::class);
});
