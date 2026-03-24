<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Page;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $frontendUrl = rtrim(env('FRONTEND_URL', config('app.url')), '/');
        // If app.url is not set correctly in .env, this might be localhost
        // For production, the user should set it to their frontend domain.
        
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // 1. Home
        $xml .= '<url>';
        $xml .= '<loc>' . $frontendUrl . '/</loc>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '</url>';

        // 2. Products
        $products = Product::where('status', 1)->get();
        foreach ($products as $product) {
            $xml .= '<url>';
            $xml .= '<loc>' . $frontendUrl . '/product/' . $product->slug . '</loc>';
            $xml .= '<lastmod>' . $product->updated_at->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        // 3. Categories
        $categories = Category::where('status', 1)->get();
        foreach ($categories as $category) {
            $xml .= '<url>';
            $xml .= '<loc>' . $frontendUrl . '/category/' . $category->slug . '</loc>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }

        // 4. Brands
        $brands = Brand::where('status', 1)->get();
        foreach ($brands as $brand) {
            $xml .= '<url>';
            $xml .= '<loc>' . $frontendUrl . '/brand/' . $brand->slug . '</loc>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.6</priority>';
            $xml .= '</url>';
        }

        // 5. Blogs
        $blogs = Blog::where('status', 1)->get();
        foreach ($blogs as $blog) {
            $xml .= '<url>';
            $xml .= '<loc>' . $frontendUrl . '/blog/' . $blog->slug . '</loc>';
            $xml .= '<lastmod>' . $blog->updated_at->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.5</priority>';
            $xml .= '</url>';
        }

        // 6. Pages
        $pages = Page::where('status', 1)->get();
        foreach ($pages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . $frontendUrl . '/page/' . $page->slug . '</loc>';
            $xml .= '<lastmod>' . $page->updated_at->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.4</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    /**
     * Get sitemap data in JSON format for frontend consumption.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function getData()
    // {
    //     $frontendUrl = rtrim(env('FRONTEND_URL', config('app.url')), '/');
    //     $data = [];

    //     // 1. Home
    //     $data[] = ['url' => $frontendUrl . '/', 'priority' => 1.0, 'changefreq' => 'daily'];

    //     // 2. Products
    //     $products = Product::where('status', 1)->get(['slug', 'updated_at']);
    //     foreach ($products as $product) {
    //         $data[] = [
    //             'url' => $frontendUrl . '/product/' . $product->slug,
    //             'lastmod' => $product->updated_at->toAtomString(),
    //             'priority' => 0.8,
    //             'changefreq' => 'weekly'
    //         ];
    //     }

    //     // 3. Categories
    //     $categories = Category::where('status', 1)->get(['slug']);
    //     foreach ($categories as $category) {
    //         $data[] = [
    //             'url' => $frontendUrl . '/category/' . $category->slug,
    //             'priority' => 0.7,
    //             'changefreq' => 'weekly'
    //         ];
    //     }

    //     // 4. Brands
    //     $brands = Brand::where('status', 1)->get(['slug']);
    //     foreach ($brands as $brand) {
    //         $data[] = [
    //             'url' => $frontendUrl . '/brand/' . $brand->slug,
    //             'priority' => 0.6,
    //             'changefreq' => 'weekly'
    //         ];
    //     }

    //     // 5. Blogs
    //     $blogs = Blog::where('status', 1)->get(['slug', 'updated_at']);
    //     foreach ($blogs as $blog) {
    //         $data[] = [
    //             'url' => $frontendUrl . '/blog/' . $blog->slug,
    //             'lastmod' => $blog->updated_at->toAtomString(),
    //             'priority' => 0.5,
    //             'changefreq' => 'weekly'
    //         ];
    //     }

    //     // 6. Pages
    //     $pages = Page::where('status', 1)->get(['slug', 'updated_at']);
    //     foreach ($pages as $page) {
    //         $data[] = [
    //             'url' => $frontendUrl . '/page/' . $page->slug,
    //             'lastmod' => $page->updated_at->toAtomString(),
    //             'priority' => 0.4,
    //             'changefreq' => 'monthly'
    //         ];
    //     }

    //     return response()->json($data);
    // }
    public function getData()
{
    $frontendUrl = rtrim(env('FRONTEND_URL', config('app.url')), '/');

    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    // 1. Home
    $xml .= '<url>';
    $xml .= '<loc>' . $frontendUrl . '/' . '</loc>';
    $xml .= '<changefreq>daily</changefreq>';
    $xml .= '<priority>1.0</priority>';
    $xml .= '</url>';

    // 2. Products
    $products = Product::where('status', 1)->get(['slug', 'updated_at']);
    foreach ($products as $product) {
        $xml .= '<url>';
        $xml .= '<loc>' . $frontendUrl . '/product/' . $product->slug . '</loc>';
        $xml .= '<lastmod>' . $product->updated_at->format('Y-m-d') . '</lastmod>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';
    }

    // 3. Categories
    $categories = Category::where('status', 1)->get(['slug']);
    foreach ($categories as $category) {
        $xml .= '<url>';
        $xml .= '<loc>' . $frontendUrl . '/category/' . $category->slug . '</loc>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.7</priority>';
        $xml .= '</url>';
    }

    // 4. Brands
    $brands = Brand::where('status', 1)->get(['slug']);
    foreach ($brands as $brand) {
        $xml .= '<url>';
        $xml .= '<loc>' . $frontendUrl . '/brand/' . $brand->slug . '</loc>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.6</priority>';
        $xml .= '</url>';
    }

    // 5. Blogs
    $blogs = Blog::where('status', 1)->get(['slug', 'updated_at']);
    foreach ($blogs as $blog) {
        $xml .= '<url>';
        $xml .= '<loc>' . $frontendUrl . '/blog/' . $blog->slug . '</loc>';
        $xml .= '<lastmod>' . $blog->updated_at->format('Y-m-d') . '</lastmod>';
        $xml .= '<changefreq>weekly</changefreq>';
        $xml .= '<priority>0.5</priority>';
        $xml .= '</url>';
    }

    // 6. Pages
    $pages = Page::where('status', 1)->get(['slug', 'updated_at']);
    foreach ($pages as $page) {
        $xml .= '<url>';
        $xml .= '<loc>' . $frontendUrl . '/page/' . $page->slug . '</loc>';
        $xml .= '<lastmod>' . $page->updated_at->format('Y-m-d') . '</lastmod>';
        $xml .= '<changefreq>monthly</changefreq>';
        $xml .= '<priority>0.4</priority>';
        $xml .= '</url>';
    }

    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'application/xml');
}
}
