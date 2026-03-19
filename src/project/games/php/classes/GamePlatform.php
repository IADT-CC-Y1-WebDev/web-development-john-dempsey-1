<?php

class GamePlatform extends Pivot {
    protected static $table = 'game_platform';
    protected static $fk1   = 'game_id';
    protected static $fk2   = 'platform_id';
}
