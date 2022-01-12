<?php
$ROOT_PATH = dirname(__DIR__);
require_once $ROOT_PATH . '/model/ResumePlayer.php';

class Database
{

    protected static $instance = null;
    private /*PDO*/
        $pdo;


    private function __construct()
    {
        try {
            $config = yaml_parse_file(dirname(__DIR__) . "/config/config.yaml");

            $databaseName = $config['databaseName'];
            $hostname = $config['hostname'];
            $username = $config['username'];
            $password = $config['password'];

            $this->pdo = new PDO("mysql:dbname=$databaseName;charset=utf8;host=$hostname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit();
        }
    }

    public static function getInstance()
    {
        if (self::$instance === NULL) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /*
     * Daily stats
     */
    public function getDailyStats()
    {
        $query = "SELECT * FROM stats_daily LIMIT 10";
        $stats = array();

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            while ($row = $stmt->fetch()) {
                $stats[] = $row;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $stats;
    }

    /*
     * ResumePlayer
     */
    public function getPlayerByName($name)
    {
        $queryUsuarios = "SELECT HEX(UUID) as uuid, username,
(SELECT SUM(amount) FROM stats_arrows_shot WHERE player_id=sp.id) AS arrows_shot,
(SELECT SUM(amount) FROM stats_beds_entered WHERE player_id=sp.id) AS beds_entered,
(SELECT SUM(amount) FROM stats_block_break WHERE player_id=sp.id) AS block_break,
(SELECT SUM(amount) FROM stats_block_place WHERE player_id=sp.id) AS block_place,
(SELECT SUM(amount) FROM stats_buckets_emptied WHERE player_id=sp.id) AS buckets_emptied,
(SELECT SUM(amount) FROM stats_commands_performed WHERE player_id=sp.id) AS commands_performed,
(SELECT SUM(amount) FROM stats_damage_taken WHERE player_id=sp.id) AS damage_taken,
(SELECT SUM(amount) FROM stats_death WHERE player_id=sp.id) AS death,
(SELECT SUM(amount) FROM stats_eggs_thrown WHERE player_id=sp.id) AS eggs_thrown,
(SELECT SUM(amount) FROM stats_fish_caught WHERE player_id=sp.id) AS fish_caught,
(SELECT SUM(amount) FROM stats_food_consumed WHERE player_id=sp.id) AS food_consumed,
(SELECT SUM(amount) FROM stats_items_crafted WHERE player_id=sp.id) AS items_crafted,
(SELECT SUM(amount) FROM stats_items_dropped WHERE player_id=sp.id) AS items_dropped,
(SELECT SUM(amount) FROM stats_items_picked_up WHERE player_id=sp.id) AS items_picked_up,
(SELECT SUM(amount) FROM stats_kill WHERE player_id=sp.id) AS `kill`,
(SELECT timestamp FROM stats_last_join WHERE player_id=sp.id ORDER BY TIMESTAMP DESC LIMIT 1) AS `last_join`,
(SELECT timestamp FROM stats_last_quit WHERE player_id=sp.id ORDER BY TIMESTAMP DESC LIMIT 1) AS `last_quit`,
(SELECT SUM(amount) FROM stats_move WHERE player_id=sp.id) AS `move`,
(SELECT SUM(amount) FROM stats_playtime WHERE player_id=sp.id) AS `playtime`,
(SELECT SUM(amount) FROM stats_pvp_kills WHERE player_id=sp.id) AS `pvp_kills`,
(SELECT SUM(amount) FROM stats_pvp_kill_streak WHERE player_id=sp.id) AS `pvp_kill_streak`,
(SELECT SUM(amount) FROM stats_teleports WHERE player_id=sp.id) AS `teleports`,
(SELECT SUM(amount) FROM stats_times_joined WHERE player_id=sp.id) AS `times_joined`,
(SELECT SUM(amount) FROM stats_times_kicked WHERE player_id=sp.id) AS `times_kicked`,
(SELECT SUM(amount) FROM stats_times_sheared WHERE player_id=sp.id) AS `times_sheared`,
(SELECT SUM(amount) FROM stats_tools_broken WHERE player_id=sp.id) AS `tools_broken`,
(SELECT count(id) FROM stats_trades_performed WHERE player_id=sp.id) AS `trades_performed`,
(SELECT SUM(amount) FROM stats_words_said WHERE player_id=sp.id) AS `words_said`,
(SELECT SUM(amount) FROM stats_xp_gained WHERE player_id=sp.id) AS `xp_gained`

FROM stats_players sp 

WHERE sp.username=:username";
        $player = NULL;

        try {
            $stmt = $this->pdo->prepare($queryUsuarios);
            $stmt->bindParam(":username", $name);
            $stmt->execute();

            if ($row = $stmt->fetch()) {
                $player = ResumePlayer::fromSql($row);
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $player;
    }

    public function getAllPlayers()
    {
        $queryUsuarios = "SELECT HEX(UUID) as uuid, username,
(SELECT SUM(amount) FROM stats_arrows_shot WHERE player_id=sp.id) AS arrows_shot,
(SELECT SUM(amount) FROM stats_beds_entered WHERE player_id=sp.id) AS beds_entered,
(SELECT SUM(amount) FROM stats_block_break WHERE player_id=sp.id) AS block_break,
(SELECT SUM(amount) FROM stats_block_place WHERE player_id=sp.id) AS block_place,
(SELECT SUM(amount) FROM stats_buckets_emptied WHERE player_id=sp.id) AS buckets_emptied,
(SELECT SUM(amount) FROM stats_commands_performed WHERE player_id=sp.id) AS commands_performed,
(SELECT SUM(amount) FROM stats_damage_taken WHERE player_id=sp.id) AS damage_taken,
(SELECT SUM(amount) FROM stats_death WHERE player_id=sp.id) AS death,
(SELECT SUM(amount) FROM stats_eggs_thrown WHERE player_id=sp.id) AS eggs_thrown,
(SELECT SUM(amount) FROM stats_fish_caught WHERE player_id=sp.id) AS fish_caught,
(SELECT SUM(amount) FROM stats_food_consumed WHERE player_id=sp.id) AS food_consumed,
(SELECT SUM(amount) FROM stats_items_crafted WHERE player_id=sp.id) AS items_crafted,
(SELECT SUM(amount) FROM stats_items_dropped WHERE player_id=sp.id) AS items_dropped,
(SELECT SUM(amount) FROM stats_items_picked_up WHERE player_id=sp.id) AS items_picked_up,
(SELECT SUM(amount) FROM stats_kill WHERE player_id=sp.id) AS `kill`,
(SELECT timestamp FROM stats_last_join WHERE player_id=sp.id ORDER BY TIMESTAMP DESC LIMIT 1) AS `last_join`,
(SELECT timestamp FROM stats_last_quit WHERE player_id=sp.id ORDER BY TIMESTAMP DESC LIMIT 1) AS `last_quit`,
(SELECT SUM(amount) FROM stats_move WHERE player_id=sp.id) AS `move`,
(SELECT SUM(amount) FROM stats_playtime WHERE player_id=sp.id) AS `playtime`,
(SELECT SUM(amount) FROM stats_pvp_kills WHERE player_id=sp.id) AS `pvp_kills`,
(SELECT SUM(amount) FROM stats_pvp_kill_streak WHERE player_id=sp.id) AS `pvp_kill_streak`,
(SELECT SUM(amount) FROM stats_teleports WHERE player_id=sp.id) AS `teleports`,
(SELECT SUM(amount) FROM stats_times_joined WHERE player_id=sp.id) AS `times_joined`,
(SELECT SUM(amount) FROM stats_times_kicked WHERE player_id=sp.id) AS `times_kicked`,
(SELECT SUM(amount) FROM stats_times_sheared WHERE player_id=sp.id) AS `times_sheared`,
(SELECT SUM(amount) FROM stats_tools_broken WHERE player_id=sp.id) AS `tools_broken`,
(SELECT count(id) FROM stats_trades_performed WHERE player_id=sp.id) AS `trades_performed`,
(SELECT SUM(amount) FROM stats_words_said WHERE player_id=sp.id) AS `words_said`,
(SELECT SUM(amount) FROM stats_xp_gained WHERE player_id=sp.id) AS `xp_gained`

FROM stats_players sp";
        $players = array();

        try {
            $stmt = $this->pdo->prepare($queryUsuarios);
            $stmt->execute();

            while ($row = $stmt->fetch()) {
                $usuario = ResumePlayer::fromSql($row);
                $players[$row["uuid"]] = $usuario;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return $players;
    }

    public function getPlayerList()
    {
        $query = "SELECT username FROM stats_players";
        $names = array();

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            while ($row = $stmt->fetch()) {
                $names[] = $row;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $names;
    }
}