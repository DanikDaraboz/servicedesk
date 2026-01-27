<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimplePdfController extends Controller
{
    public function index()
    {
        // Простейший ответ без зависимостей
        return response()->json([
            'status' => 'success',
            'message' => 'PDF модуль работает!',
            'timestamp' => now(),
            'routes' => [
                'upload' => '/pdf/upload',
                'history' => '/pdf/history',
                'editor' => '/pdf/editor/{uuid}'
            ]
        ]);
    }
    
    public function demo()
    {
        return '<!DOCTYPE html>
        <html>
        <head>
            <title>PDF Signer Demo</title>
            <script src=https://cdn.tailwindcss.com></script>
        </head>
        <body class=bg-gray-100
