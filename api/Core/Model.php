<?php

namespace Api\Core;

use PDO;
use BackedEnum;
use Api\Core\DB;
use ReflectionClass;
use ReflectionProperty;
use Api\Core\Collection;

use function Api\Helpers\dd;

use InvalidArgumentException;

abstract class Model
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    const EXCLUDE_PROPS = [
        'db',
        'table',
        'primaryKey',
    ];

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

    public static function all(): array
    {
        $instance = new static();
        $request = $instance
            ->db
            ->prepare('SELECT * FROM `' . $instance->getTable() . '`');
        $request->execute();
        return new Collection(
            $instance->getObjects(
                $request->fetchAll(PDO::FETCH_ASSOC)
            )
        )
            ->toArray();
    }

    public static function findBy(
        string $column,
        mixed $value
    ): Collection {
        $instance = new static();
        if (!in_array($column, $instance->getModelVars()->keys(), true)) {
            throw new InvalidArgumentException('Column ' . $column . ' does not exist in model.');
        }

        $request = $instance
            ->db
            ->prepare('SELECT * FROM `' . $instance->getTable() . '` WHERE `' . $column . '` = :value');
        $request->bindParam(':value', $value);
        $request->execute();
        return new Collection(
            $instance->getObjects(
                $request->fetchAll(PDO::FETCH_ASSOC)
            )
        );
    }

    public static function findByID(
        int $id
    ): ?self {
        $instance = new static();
        return self::findBy($instance->getPrimaryKey(), $id)
            ->first();
    }


    public static function create(
        array $params = []
    ): string|int {
        $instance = new static();
        $params = $instance->filterParams($params);

        $placeholders = $instance->createRequestPlaceholders($params, ':{column}');
        $cols = $instance->createRequestPlaceholders($params, '`{column}`');
        $request = $instance
            ->db
            ->prepare('INSERT INTO `' . $instance->getTable() . '` (' . $cols . ') VALUES (' . $placeholders . ')');
        foreach ($params as $key => $value) {
            $request->bindValue(":$key", static::IfEnumValue($value));
        }
        $request->execute();

        return $instance->db->lastInsertId();
    }

    public static function update(
        string|int $primaryKey,
        array $params
    ): void {
        $instance = new static();
        $params = $instance->filterParams($params);

        $set = $instance->createRequestPlaceholders($params, '`{column}` = :{column}');
        $request = $instance
            ->db
            ->prepare('UPDATE `' . $instance->getTable() . '` SET ' . $set . ' WHERE `' . $instance->getPrimaryKey() . '` = :primaryKey');
        foreach ($params as $key => $value) {
            $request->bindValue(":$key", static::IfEnumValue($value));
        }
        $request->bindValue(':primaryKey', $primaryKey);
        $request->execute();
    }


    public function delete(): bool
    {
        $request = $this
            ->db
            ->prepare('DELETE FROM `' . $this->getTable() . '` WHERE `' . $this->getPrimaryKey() . '` = :primaryKey');
        $request->bindValue(':primaryKey', $this->{$this->getPrimaryKey()});
        return $request->execute();
    }


    public function save(): void
    {
        $vars = $this->getModelVars()->toArray();
        $primaryKey = $this->{$this->getPrimaryKey()} ?? null;

        if ($primaryKey !== null && static::findBy($this->getPrimaryKey(), $primaryKey)->count() > 0) {
            self::update($this->{$this->getPrimaryKey()}, $vars);
        } else {
            self::create($vars);
        }
    }


    private function getObject(
        array $data
    ): self {
        $instance = new static();
        foreach ($data as $key => $val) {
            if (!property_exists($instance, $key)) {
                continue;
            }

            $reflectionProperty = new \ReflectionProperty($instance, $key);
            $type = $reflectionProperty->getType();

            if ($type && !$type->isBuiltin()) {
                $typeName = $type->getName();
                if (enum_exists($typeName)) {
                    $val = $typeName::from($val);
                }
            }

            $instance->$key = $val;
        }
        return $instance;
    }

    private function getObjects(
        array $rows
    ): array {
        return array_map(
            fn($row) => $this->getObject($row),
            $rows
        );
    }

    private function getModelVars(): Collection
    {
        $vars = [];
        $props = (new ReflectionClass($this))
            ->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($props as $prop) {
            $name = $prop->getName();
            $vars[$name] = $this->$name ?? null;
        }

        foreach (self::EXCLUDE_PROPS as $prop) {
            unset($vars[$prop]);
        }
        return new Collection($vars ?? []);
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
        return array_filter(
            $params,
            fn($key) => in_array($key, $this->getModelVars()->keys(), true),
            ARRAY_FILTER_USE_KEY
        );
    }

    private static function IfEnumValue(
        mixed $value
    ): mixed {
        return $value instanceof BackedEnum ? $value->value : $value;
    }
}
