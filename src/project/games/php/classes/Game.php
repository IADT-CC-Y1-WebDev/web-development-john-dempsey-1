<?php

class Game extends Model {
    protected static $table = 'games';
    protected static $orderBy = 'title';

    public $id;
    public $title;
    public $release_date;
    public $genre_id;
    public $description;
    public $image_filename;

    // Get the genre this game belongs to
    public function genre(): ?Genre {
        return $this->belongsTo(Genre::class, 'genre_id');
    }

    // Get all platforms for this game (many-to-many via game_platform)
    public function platforms(): array {
        return $this->belongsToMany(Platform::class, 'game_platform', 'game_id', 'platform_id');
    }
}
