<?php
namespace Repositories;

use PDO;
use PDOException;
use Exception;

use Repositories\Repository;
use Models\Size;
use Models\Category;

class SizeRepository extends Repository
{
    public function getAll()
    {
        try {
            $query = "SELECT * FROM Sizes
                      JOIN Categories
                      ON Sizes.category_id = Categories.category_id";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            $sizes = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $sizes[] = $this->rowToSize($row);
            }

            return $sizes;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function rowToSize($row)
    {
        $size = new Size();
        $size->size_id = $row['size_id'];
        $size->size_name = $row['size_name'];
        $size->category = new Category();
        $size->category->category_id = $row['category_id'];
        $size->category->category_name = $row['category_name'];

        return $size;
    }
}
