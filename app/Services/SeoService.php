<?php

namespace App\Services;

class SeoService
{
    protected string $title = 'Nepal Education Management System';
    protected string $description = 'Complete cloud platform to manage your educational institution efficiently.';
    protected string $image = '';
    protected string $type = 'website';
    protected string $canonical = '';

    public function __construct()
    {
        $this->canonical = url()->current();
    }

    public function setTitle(string $title): static
    {
        $this->title = substr($title, 0, 60);
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDescription(string $description): static
    {
        $this->description = substr($description, 0, 160);
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setCanonical(string $url): static
    {
        $this->canonical = $url;
        return $this;
    }

    public function getCanonical(): string
    {
        return $this->canonical;
    }

    public function renderTags(): string
    {
        $html = [];
        
        $html[] = '<title>' . e($this->title) . '</title>';
        $html[] = '<meta name="description" content="' . e($this->description) . '">';
        
        // Open Graph tags
        $html[] = '<meta property="og:title" content="' . e($this->title) . '">';
        $html[] = '<meta property="og:description" content="' . e($this->description) . '">';
        $html[] = '<meta property="og:type" content="' . e($this->type) . '">';
        $html[] = '<meta property="og:url" content="' . e($this->canonical) . '">';
        if ($this->image) {
            $html[] = '<meta property="og:image" content="' . e($this->image) . '">';
        }

        // Canonical
        $html[] = '<link rel="canonical" href="' . e($this->canonical) . '">';

        return implode("\n    ", $html);
    }

    public function renderJsonLd(array $data = []): string
    {
        $default = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $this->title,
            'url' => url('/'),
        ];
        
        $schema = array_merge($default, $data);
        
        return '<script type="application/ld+json">' . "\n" . json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n" . '</script>';
    }
}
