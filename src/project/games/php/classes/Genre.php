<?php

class Genre extends Model {
    protected static $table = 'genres';
    protected static $orderBy = 'name';

    public $id;
    public $name;
    public $description;

    // Get all games in this genre (one-to-many)
    public function games(): array {
        return $this->hasMany(Game::class, 'genre_id');
    }
}
