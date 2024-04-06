<?php

namespace Repositories;

use PDO;
use PDOException;
use Exception;
use Repositories\Repository;

class CategoryRepository extends Repository
{
    public function getAll()
    {
        try {
            $query = "SELECT * FROM Categories";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Category');
            $articles = $stmt->fetchAll();

            return $articles;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getOne($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM category WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Category');
            $product = $stmt->fetch();

            return $product;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function insert($category)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into category (name) VALUES (?)");

            $stmt->execute([$category->name]);

            $category->id = $this->connection->lastInsertId();

            return $category;
        } catch (PDOException $e) {
            echo $e;
        }
    }


    public function update($category, $id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE category SET name = ? WHERE id = ?");

            $stmt->execute([$category->name, $id]);

            return $category;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM category WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return;
        } catch (PDOException $e) {
            echo $e;
        }
        return true;
    }
}
