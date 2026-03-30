<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\SeoService;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function __construct(protected SeoService $seo)
    {
    }

    public function home()
    {
        $this->seo->setTitle(__('Home - Nepal Education System'))
                  ->setDescription(__('Discover the best cloud platform for managing schools, colleges, and educational institutes.'));

        return view('site.home', ['seo' => $this->seo]);
    }

    public function features()
    {
        $this->seo->setTitle(__('Features - Nepal Education System'))
                  ->setDescription(__('Explore the feature-rich multi-tenant system for education management.'));

        return view('site.features', ['seo' => $this->seo]);
    }

    public function pricing()
    {
        $this->seo->setTitle(__('Pricing - Nepal Education System'))
                  ->setDescription(__('Affordable pricing plans for institutions of all sizes.'));

        return view('site.pricing', ['seo' => $this->seo]);
    }

    public function about()
    {
        $this->seo->setTitle(__('About Us - Nepal Education System'))
                  ->setDescription(__('Learn about our mission to revolutionize education management in Nepal.'));

        return view('site.about', ['seo' => $this->seo]);
    }

    public function contact()
    {
        $this->seo->setTitle(__('Contact Us - Nepal Education System'))
                  ->setDescription(__('Get in touch with us for inquiries, support, or partnership opportunities.'));

        return view('site.contact', ['seo' => $this->seo]);
    }

    public function blogIndex()
    {
        $this->seo->setTitle(__('Blog - Nepal Education System'))
                  ->setDescription(__('Read the latest updates, tips, and news about education management.'));

        return view('site.blog.index', ['seo' => $this->seo]);
    }

    public function blogShow($slug)
    {
        // Example dummy content since no DB blog is specified
        $this->seo->setTitle(__('Blog Detail - Nepal Education System'))
                  ->setDescription(__('Detailed view of our specific blog topic.'))
                  ->setType('article');

        return view('site.blog.show', ['seo' => $this->seo, 'slug' => $slug]);
    }

    public function sitemap()
    {
        $urls = [
            url('/'),
            url('/features'),
            url('/pricing'),
            url('/about'),
            url('/contact'),
            url('/blog'),
        ];

        return response()->view('site.sitemap', ['urls' => $urls])
                         ->header('Content-Type', 'text/xml');
    }

    public function robots()
    {
        $content = "User-agent: *\nDisallow:\nSitemap: " . url('/sitemap.xml');
        return response($content)->header('Content-Type', 'text/plain');
    }
}
