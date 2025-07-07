<?php

require_once __DIR__ . '/../controllers/BaseController.php';
require_once __DIR__ . '/../models/Movie.php';
require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../connection/cors.php';

class MovieController extends BaseController
{
    public function getAllMovies()
    {
        global $mysqli;

        try {
            $stmt = $mysqli->prepare("SELECT * FROM movies");
            $stmt->execute();

            $result = $stmt->get_result();
            $movies = [];

            while ($row = $result->fetch_assoc()) {
                $movie = new Movie($row);
                $movies[] = $movie->toArray();
            }

            self::success(["movies" => $movies]);
        } catch (Exception $e) {
            self::error("Server error: " . $e->getMessage(), 500);
        }
    }

    public function getReleasedMovies()
    {
        global $mysqli;

        try {
            $sql = "SELECT * FROM movies WHERE release_date <= CURRENT_DATE() ORDER BY popularity DESC";
            $stmt = $mysqli->prepare($sql);
            $stmt->execute();

            $result = $stmt->get_result();
            $movies = [];

            while ($row = $result->fetch_assoc()) {
                $movie = new Movie($row);
                $movies[] = $movie->toArray();
            }

            self::success(["movies" => $movies]);
        } catch (Exception $e) {
            self::error("Server error: " . $e->getMessage(), 500);
        }
    }

    public function getNowShowing()
    {
        global $mysqli;

        try {
            $sql = "
                SELECT DISTINCT m.*
                FROM movies m
                INNER JOIN showtimes s ON m.id = s.movie_id
                WHERE CURRENT_DATE() BETWEEN s.start_date AND s.end_date
                ORDER BY popularity DESC
            ";
            $stmt = $mysqli->prepare($sql);
            $stmt->execute();

            $result = $stmt->get_result();
            $movies = [];

            while ($row = $result->fetch_assoc()) {
                $movie = new Movie($row);
                $movies[] = $movie->toArray();
            }

            self::success(["now_showing" => $movies]);
        } catch (Exception $e) {
            self::error("Server error: " . $e->getMessage(), 500);
        }
    }

    public function getUpcoming()
    {
        global $mysqli;

        try {
            $sql = "
                SELECT DISTINCT *
                FROM movies
                WHERE CURRENT_DATE() < release_date
                ORDER BY popularity DESC, release_date ASC
                LIMIT 10
            ";
            $stmt = $mysqli->prepare($sql);
            $stmt->execute();

            $result = $stmt->get_result();
            $movies = [];

            while ($row = $result->fetch_assoc()) {
                $movie = new Movie($row);
                $movies[] = $movie->toArray();
            }

            self::success(["upcoming" => $movies]);
        } catch (Exception $e) {
            self::error("Server error: " . $e->getMessage(), 500);
        }
    }
}
