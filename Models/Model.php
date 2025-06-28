<?php
abstract class Model
{

    protected static string $table;
    protected static string $primary_key = "id";

    public static function find(mysqli $mysqli, int $id)
    {
        $sql = sprintf(
            "Select * from %s WHERE %s = ?",
            static::$table,
            static::$primary_key
        );

        $query = $mysqli->prepare($sql);
        $query->bind_param("i", $id);
        $query->execute();

        $data = $query->get_result()->fetch_assoc();

        return $data ? new static($data) : null;
    }

    public static function all(mysqli $mysqli)
    {
        $sql = sprintf("Select * from %s", static::$table);

        $query = $mysqli->prepare($sql);
        $query->execute();

        $data = $query->get_result();

        $objects = [];
        while ($row = $data->fetch_assoc()) {
            $objects[] = new static($row); //creating an object of type "static" / "parent" and adding the object to the array
        }

        return $objects; //we are returning an array of objects!!!!!!!!
    }
}
