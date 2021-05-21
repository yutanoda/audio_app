<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class Post
{
    public $title;

    public $excerpt;

    public $date;

    public $body;

    public $slug;

    public function __construct($title, $excerpt, $date, $body, $slug)
    {
      $this->title = $title;
      $this->excerpt = $excerpt;
      $this->date = $date;
      $this->body = $body;
      $this->slug = $slug;
    }

    public static function all()
    {
      return cache()->rememberForever('posts.all', function() {
        return collect(File::files(resource_path("posts")))
          ->map(fn($file) => YamlFrontMatter::parseFile($file))
          ->map(fn($document) => new Post(
            $document->title,
            $document->excerpt,
            $document->date,
            $document->body(),
            $document->slug
          ))
          ->sortBy('date');
      });
    }

    public static function find($slug)
    {
      //if (!file_exists($path = resource_path("posts/{$slug}.html"))) {
      //    //return redirect('/');
      //    throw new ModelNotFoundException();
      //}
      //
      //return cache()->remember("posts.{$slug}", 1200, function () use ($path) {
      //    return file_get_contents($path);
      //});

      return static::all()->firstWhere('slug', $slug);

    }

    public static function findOrFail($slug)
    {
      //if (!file_exists($path = resource_path("posts/{$slug}.html"))) {
      //    //return redirect('/');
      //    throw new ModelNotFoundException();
      //}
      //
      //return cache()->remember("posts.{$slug}", 1200, function () use ($path) {
      //    return file_get_contents($path);
      //});

      $post = static::find($slug);

      if (! $post) {
        throw new ModelNotFoundException();
      }
      return $post;

    }
}