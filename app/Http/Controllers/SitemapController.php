<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $categories = Category::where('status', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $services = Service::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->limit(5000)
            ->get();

        $content = view('sitemap.index', compact('categories', 'services'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
