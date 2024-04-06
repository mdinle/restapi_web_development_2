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
}
