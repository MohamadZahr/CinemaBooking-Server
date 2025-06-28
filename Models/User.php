<?php
require_once("Model.php");

class User extends Model
{

    private int $id;
    private string $full_name;
    private string $email;
    private string $password_hash;
    private string $role;
    private string $created_at;

    protected static string $table = "users";

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->full_name = $data["full_name"];
        $this->email = $data["email"];
        $this->password_hash = $data["password"];
        $this->role = $data["role"];
        $this->created_at = $data["created_at"] ?? date('Y-m-d H:i:s');
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }
    public function getFullName(): string
    {
        return $this->full_name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }
    public function getRole(): string
    {
        return $this->role;
    }
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    // Setters
    public function setFullName(string $name): void
    {
        $this->full_name = $name;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setPassword(string $password): void
    {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    public static function findByEmail(mysqli $mysqli, string $email): ?User
    {
        $sql = sprintf("SELECT * FROM %s WHERE email = ?", static::$table);
        $query = $mysqli->prepare($sql);
        $query->bind_param("s", $email);
        $query->execute();

        $result = $query->get_result();
        return $result->num_rows > 0
            ? new static($result->fetch_assoc())
            : null;
    }

    public static function create(mysqli $mysqli, array $data): ?User {
        $sql = "INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $hashed = password_hash($data['password'], PASSWORD_BCRYPT);
        $role = $data['role'] ?? 'user';

        $stmt->bind_param("ssss", $data['full_name'], $data['email'], $hashed, $role);

        if (!$stmt->execute()) {
            return null;
        }

        $id = $stmt->insert_id;
        return self::find($mysqli, $id);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'role' => $this->role,
            'created_at' => $this->created_at
        ];
    }
    
}
