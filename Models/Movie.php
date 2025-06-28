<?php
require_once("Model.php");

class Movie extends Model {

    private int $id;
    private string $title;
    private ?string $description;
    private ?float $rating;
    private ?string $poster_path;
    private ?float $popularity;
    private ?string $release_date;
    private bool $now_showing;

    protected static string $table = "movies";

    public function __construct(array $data) {
        $this->id = (int) $data['id'];
        $this->title = $data['title'];
        $this->description = $data['description'] ?? null;
        $this->rating = isset($data['rating']) ? (float) $data['rating'] : null;
        $this->poster_path = $data['poster_path'] ?? null;
        $this->popularity = isset($data['popularity']) ? (float) $data['popularity'] : null;
        $this->release_date = $data['release_date'] ?? null;
        $this->now_showing = (bool) $data['now_showing'];
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getRating(): ?float {
        return $this->rating;
    }

    public function getPosterPath(): ?string {
        return $this->poster_path;
    }

    public function getPopularity(): ?float {
        return $this->popularity;
    }

    public function getReleaseDate(): ?string {
        return $this->release_date;
    }

    public function isNowShowing(): bool {
        return $this->now_showing;
    }

    // Setters (optional)
    public function setTitle(string $title) {
        $this->title = $title;
    }

    public function setDescription(?string $description) {
        $this->description = $description;
    }

    public function setRating(?float $rating) {
        $this->rating = $rating;
    }

    public function setPosterPath(?string $path) {
        $this->poster_path = $path;
    }

    public function setPopularity(?float $popularity) {
        $this->popularity = $popularity;
    }

    public function setReleaseDate(?string $date) {
        $this->release_date = $date;
    }

    public function setNowShowing(bool $nowShowing) {
        $this->now_showing = $nowShowing;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'rating' => $this->rating,
            'poster_path' => $this->poster_path,
            'popularity' => $this->popularity,
            'release_date' => $this->release_date,
            'now_showing' => $this->now_showing
        ];
    }
}
