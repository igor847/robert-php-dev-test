<?php

namespace Api\Core;

use PDO;
use Api\Core\DB;
use App\Core\Collection;
use InvalidArgumentException;

abstract class Model
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = DB::get();
    }

    final protected function getTable(): string
    {
        return $this->table;
    }

    final protected function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    public static function all(): Collection
    {
        $instance = new static();
        $request = $instance->db->prepare('SELECT * FROM `' . $instance->getTable() . '`');
        $request->execute();
        return new Collection(
            static::getObjects(
                $request->fetchAll(PDO::FETCH_ASSOC)
            )
        );
    }

    public static function findBy(
        string $column,
        mixed $value
    ): Collection {
        $instance = new static();
        if (!in_array($column, array_keys($instance->getModelVars()), true)) {
            throw new InvalidArgumentException('Column ' . $column . ' does not exist in model.');
        }

        $request = $instance->db->prepare('SELECT * FROM `' . $instance->getTable() . '` WHERE `' . $column . '` = :value');
        $request->bindParam(':value', $value, PDO::PARAM_STR);
        $request->execute();
        return new Collection(
            static::getObjects(
                $request->fetchAll(PDO::FETCH_ASSOC)
            )
        );
    }

    public static function findByID(
        int $id
    ): mixed {
        return static::findBy(static::getPrimaryKey(), $id)
            ->first();
    }

    public static function create(
        array $params = []
    ): ?int {
        $instance = new static();
        $params = $instance->filterParams($params);

        $placeholders = $instance->createRequestPlaceholders($params, ':{column}');
        $cols = $instance->createRequestPlaceholders($params, '`{column}`');
        $request = $instance
            ->db
            ->prepare('INSERT INTO `' . $instance->getTable() . '` (' . $cols . ') VALUES (' . $placeholders . ')');
        foreach ($params as $key => $value) {
            $request->bindValue(":$key", $value);
        }
        $request->execute();

        return $instance->db->lastInsertId();
    }

    public static function update(
        int $id,
        array $params
    ) {
        $instance = new static();
        $params = $instance->filterParams($params);

        $set = $instance->createRequestPlaceholders($params, '`{column}` = :{column}');
        $request = $instance
            ->db
            ->prepare('UPDATE `' . $instance->getTable() . '` SET ' . $set . ' WHERE `' . $instance->getPrimaryKey() . '` = :__id');
        foreach ($params as $key => $value) {
            $request->bindValue(":$key", $value);
        }
        $request->bindValue(':__id', $id);
        $request->execute();
    }


    public static function delete(
        int $id
    ): bool {
        $instance = new static();
        $request = $instance
            ->db
            ->prepare('DELETE FROM `' . $instance->getTable() . '` WHERE `' . $instance->getPrimaryKey() . '` = :id');
        $request->bindValue(':id', $id, \PDO::PARAM_INT);
        return $request->execute();
    }


    public function save(): void
    {
        $vars = $this->getModelVars();

        if (isset($this->{$this->primaryKey}) && $this->{$this->primaryKey} !== null) {
            self::update($this->{$this->primaryKey}, $vars);
        } else {
            self::create($vars);
        }
    }


    private function getObject(
        array $data
    ): self {
        $model = new self();
        foreach ($data as $key => $val) {
            if (property_exists($model, $key)) {
                $model->$key = $val;
            }
        }
        return $model;
    }

    private function getObjects(
        array $rows
    ): array {
        return array_map(
            fn($row) => static::getObject($row),
            $rows
        );
    }

    private function getModelVars(): array
    {
        $props = get_object_vars($this);
        unset($props['db'], $props['table'], $props['primaryKey']);
        return $props;
    }

    private function createRequestPlaceholders(
        array $vars,
        string $template
    ): string {
        return implode(
            ', ',
            array_map(
                fn($c) => str_replace('{column}', $c, $template),
                array_keys($vars)
            )
        );
    }

    private function filterParams(
        array $params
    ): array {
        $allowed = array_keys($this->getModelVars());
        return array_filter(
            $params,
            fn($key) => in_array($key, $allowed, true),
            ARRAY_FILTER_USE_KEY
        );
    }
}
