<?php

abstract class Pivot {
    // Subclass must define these three properties
    protected static $table;
    protected static $fk1;
    protected static $fk2;

    // Check if a relationship exists between the two IDs
    public static function exists($id1, $id2): bool {
        $db   = DB::getInstance()->getConnection();
        $stmt = $db->prepare(
            "SELECT COUNT(*) FROM `" . static::$table . "`
             WHERE `" . static::$fk1 . "` = :id1
             AND `" . static::$fk2 . "` = :id2"
        );
        $stmt->execute(['id1' => $id1, 'id2' => $id2]);
        return (bool) $stmt->fetchColumn();
    }

    // Sync related IDs for a given foreign key, inserting new rows and deleting removed ones.
    // e.g. GamePlatform::sync('game_id', $gameId, $platformIds)
    //      GamePlatform::sync('platform_id', $platformId, $gameIds)
    public static function sync(string $foreignKey, $id, array $relatedIds): void {
        $otherFk = ($foreignKey === static::$fk1) ? static::$fk2 : static::$fk1;
        $db      = DB::getInstance()->getConnection();

        // Fetch current related IDs
        $stmt = $db->prepare(
            "SELECT `{$otherFk}` FROM `" . static::$table . "` WHERE `{$foreignKey}` = :id"
        );
        $stmt->execute(['id' => $id]);
        $currentIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $toAdd    = array_diff($relatedIds, $currentIds);
        $toRemove = array_diff($currentIds, $relatedIds);

        foreach ($toRemove as $relatedId) {
            $stmt = $db->prepare(
                "DELETE FROM `" . static::$table . "`
                 WHERE `{$foreignKey}` = :id AND `{$otherFk}` = :related_id"
            );
            $stmt->execute(['id' => $id, 'related_id' => $relatedId]);
        }

        foreach ($toAdd as $relatedId) {
            $stmt = $db->prepare(
                "INSERT INTO `" . static::$table . "` (`{$foreignKey}`, `{$otherFk}`)
                 VALUES (:id, :related_id)"
            );
            $stmt->execute(['id' => $id, 'related_id' => $relatedId]);
        }
    }
}
