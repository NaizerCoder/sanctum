<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetController extends Controller
{
    public function __invoke()
    {
        return response(['content' => 'Получили контент из Контроллера при обращении к защищенному роуту (middleware - auth:sanctum)']);
    }
}
