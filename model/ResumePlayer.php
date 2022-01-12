<?php

/**
 * Jugador resumen, con sus cantidades numericas sin desglose
 */
class ResumePlayer
{

    public $uuid;
    public $username;
    public $arrows_shot;
    public $beds_entered;
    public $block_break;
    public $block_place;
    public $buckets_emptied;
    public $commands_performed;
    public $damage_taken;
    public $death;
    public $eggs_thrown;
    public $fish_caught;
    public $food_consumed;
    public $items_crafted;
    public $items_dropped;
    public $items_picked_up;
    public $kill;
    public $last_join;
    public $last_quit;
    public $move;
    public $playtime;
    public $pvp_kills;
    public $pvp_kill_streak;
    public $teleports;
    public $times_joined;
    public $times_kicked;
    public $times_sheared;
    public $tools_broken;
    public $trades_performed;
    public $words_said;
    public $xp_gained;

    public function __construct($uuid, $username, $arrows_shot, $beds_entered, $block_break, $block_place, $buckets_emptied, $commands_performed, $damage_taken, $death, $eggs_thrown, $fish_caught, $food_consumed, $items_crafted, $items_dropped, $items_picked_up, $kill, $last_join, $last_quit, $move, $playtime, $pvp_kills, $pvp_kill_streak, $teleports, $times_joined, $times_kicked, $times_sheared, $tools_broken, $trades_performed, $words_said, $xp_gained)
    {
        $this->uuid = $uuid;
        $this->username = $username;
        $this->arrows_shot = $arrows_shot;
        $this->beds_entered = $beds_entered;
        $this->block_break = $block_break;
        $this->block_place = $block_place;
        $this->buckets_emptied = $buckets_emptied;
        $this->commands_performed = $commands_performed;
        $this->damage_taken = $damage_taken;
        $this->death = $death;
        $this->eggs_thrown = $eggs_thrown;
        $this->fish_caught = $fish_caught;
        $this->food_consumed = $food_consumed;
        $this->items_crafted = $items_crafted;
        $this->items_dropped = $items_dropped;
        $this->items_picked_up = $items_picked_up;
        $this->kill = $kill;
        $this->last_join = $last_join;
        $this->last_quit = $last_quit;
        $this->move = $move;
        $this->playtime = $playtime;
        $this->pvp_kills = $pvp_kills;
        $this->pvp_kill_streak = $pvp_kill_streak;
        $this->teleports = $teleports;
        $this->times_joined = $times_joined;
        $this->times_kicked = $times_kicked;
        $this->times_sheared = $times_sheared;
        $this->tools_broken = $tools_broken;
        $this->trades_performed = $trades_performed;
        $this->words_said = $words_said;
        $this->xp_gained = $xp_gained;
    }

    public static function fromSql($row)
    {
        return new ResumePlayer($row["uuid"], $row["username"], $row["arrows_shot"], $row["beds_entered"], $row["block_break"],
            $row["block_place"], $row["buckets_emptied"], $row["commands_performed"], $row["damage_taken"], $row["death"],
            $row["eggs_thrown"], $row["fish_caught"], $row["food_consumed"], $row["items_crafted"],
            $row["items_dropped"], $row["items_picked_up"], $row["kill"], $row["last_join"], $row["last_quit"],
            $row["move"], $row["playtime"], $row["pvp_kills"], $row["pvp_kill_streak"], $row["teleports"],
            $row["times_joined"], $row["times_kicked"], $row["times_sheared"], $row["tools_broken"],
            $row["trades_performed"], $row["words_said"], $row["xp_gained"]);
    }
}
