<?php

namespace App\Support;

use Illuminate\Contracts\Support\Arrayable;

class SeoData implements Arrayable
{
    public function __construct(
        public string $title = '',
        public string $description = '',
        public string $keywords = '',
        public string $image = '/screenshots/14-dashboard-widgets.png',
        public string $url = '',
        public string $type = 'website',
        public string $author = 'Laravilt',
        public ?string $publishedTime = null,
        public ?string $modifiedTime = null,
        public ?string $section = null,
        public bool $noindex = false,
    ) {}

    public static function make(string $title = ''): static
    {
        return new static(title: $title);
    }

    public function title(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function description(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function keywords(string $keywords): static
    {
        $this->keywords = $keywords;
        return $this;
    }

    public function image(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function url(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function type(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function article(?string $publishedTime = null, ?string $modifiedTime = null, ?string $section = null): static
    {
        $this->type = 'article';
        $this->publishedTime = $publishedTime;
        $this->modifiedTime = $modifiedTime;
        $this->section = $section;
        return $this;
    }

    public function noindex(bool $noindex = true): static
    {
        $this->noindex = $noindex;
        return $this;
    }

    public function getFullTitle(): string
    {
        $siteName = config('app.name', 'Laravilt');

        if (empty($this->title) || $this->title === 'Home' || $this->title === $siteName) {
            return "{$siteName} - Modern Admin Panel Framework for Laravel + Vue";
        }

        return "{$this->title} | {$siteName}";
    }

    public function getFullUrl(): string
    {
        if (empty($this->url)) {
            return url()->current();
        }

        if (str_starts_with($this->url, 'http')) {
            return $this->url;
        }

        return url($this->url);
    }

    public function getFullImageUrl(): string
    {
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return url($this->image);
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'fullTitle' => $this->getFullTitle(),
            'description' => $this->description ?: $this->getDefaultDescription(),
            'keywords' => $this->keywords ?: $this->getDefaultKeywords(),
            'image' => $this->getFullImageUrl(),
            'url' => $this->getFullUrl(),
            'type' => $this->type,
            'author' => $this->author,
            'publishedTime' => $this->publishedTime,
            'modifiedTime' => $this->modifiedTime,
            'section' => $this->section,
            'noindex' => $this->noindex,
            'siteName' => config('app.name', 'Laravilt'),
            'twitterHandle' => '@laravilt',
        ];
    }

    protected function getDefaultDescription(): string
    {
        return 'Laravilt is a modern admin panel framework for Laravel and Vue.js. Build beautiful, type-safe admin panels with forms, tables, widgets, and AI integration.';
    }

    protected function getDefaultKeywords(): string
    {
        return 'laravel, vue, admin panel, crud, forms, tables, typescript, inertia, filament alternative';
    }
}
