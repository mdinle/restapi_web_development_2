<?php
namespace Repositories;

use PDO;
use PDOException;
use Exception;
use Repositories\Repository;

class BrandRepository extends Repository
{
    public function getAll()
    {
        try {
            $query = "SELECT * FROM Brands";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Brand');
            $brands = $stmt->fetchAll();

            return $brands;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getOne($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM Brands WHERE brand_id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Brand');
            $brand = $stmt->fetch();

            return $brand;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function insert($brand)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into Brands (brand_name, description) VALUES (?, ?)");

            $stmt->execute([$brand->brand_name, $brand->description]);

            $brand->brand_id = $this->connection->lastInsertId();

            return $brand;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                if(strpos($e->getMessage(), 'brand_name') !== false) {
                    throw new Exception('Brand already exists');
                }
            } else {
                throw new Exception($e->getMessage());
            }
        }
    }


    public function update($brand)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Brands SET brand_name = ?, description = ? WHERE brand_id = ?");

            $stmt->execute([$brand->brand_name, $brand->description, $brand->brand_id]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                if(strpos($e->getMessage(), 'brand_name') !== false) {
                    throw new Exception('Brand already exists');
                }
            } else {
                throw new Exception($e->getMessage());
            }
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM Brands WHERE brand_id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                if(strpos($e->getMessage(), 'Cannot delete or update a parent row') !== false) {
                    throw new Exception('Brand in use, failed to delete.');
                }
            } else {
                throw new Exception($e->getMessage());
            }
        }
    }
}
