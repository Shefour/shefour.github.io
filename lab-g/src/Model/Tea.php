<?php

namespace App\Model;

use App\Service\Config;

class Tea
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $type = null;
    private ?string $description = null;

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM tea';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $teas = [];
        $teasArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($teasArray as $teaArray) {
            $teas[] = self::fromArray($teaArray);
        }

        return $teas;
    }

    public function fill($array): Tea
    {
        if (isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['type'])) {
            $this->setType($array['type']);
        }
        if (isset($array['description'])) {
            $this->setDescription($array['description']);
        }
        return $this;
    }

    public static function fromArray(array $teaArray): Tea
    {
        $tea = new self();
        $tea->fill($teaArray);

        return $tea;
    }

    public static function find(int $id): ?Tea
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM tea WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $teaArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $teaArray) {
            return null;
        }
        $tea = Tea::fromArray($teaArray);

        return $tea;
    }

    public function getId(): ?int {return $this->id;}
    public function getName(): ?string {return $this->name;}
    public function getType(): ?string {return $this->type;}
    public function getDescription(): ?string {return $this->description;}

    public function setId(?int $id): Tea
    {
        $this->id = $id;
        return $this;
    }
    public function setName(?string $name): Tea
    {
        $this->name = $name;
        return $this;
    }

    public function setType(?string $type): Tea
    {
        $this->type = $type;
        return $this;
    }
    public function setDescription(?string $description): Tea
    {
        $this->description = $description;
        return $this;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (! $this->getId()) {
            $sql = "INSERT INTO tea (subject, content, description) VALUES (:name, :type, :description)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':name' => $this->getName(),
                ':type' => $this->getType(),
                ':description' => $this->getDescription()
            ]);

            $this->setId($pdo->lastInsertId());
        } else {
            $sql = "UPDATE tea SET name = :name, type = :type, description = :description WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'name' => $this->getName(),
                'type' => $this->getType(),
                'description' => $this->getDescription(),
                ':id' => $this->getId(),
            ]);
        }
    }

    public function delete(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM tea WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':id' => $this->getId(),
        ]);

        $this->setId(null);
        $this->setName(null);
        $this->setType(null);
        $this->setDescription(null);
    }
}