<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    /**
     * Handle 404 error
     */
    public function notFound()
    {
        return response()->view('block.404', [], 404);
    }

    /**
     * Handle 403 error
     */
    public function forbidden()
    {
        return response()->view('block.403', [], 403);
    }
}
