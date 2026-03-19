<?php

class Platform extends Model {
    protected static $table = 'platforms';
    protected static $orderBy = 'name';

    public $id;
    public $name;
    public $manufacturer;

    // Get all games for this platform (many-to-many via game_platform)
    public function games(): array {
        return $this->belongsToMany(Game::class, 'game_platform', 'platform_id', 'game_id');
    }
}
