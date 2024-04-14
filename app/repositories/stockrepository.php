<?php
namespace Repositories;

use Models\Product;
use Models\Size;
use Models\Stock;
use Models\StockHistory;
use PDO;
use PDOException;
use Exception;
use DateTime;

use Repositories\Repository;

class StockRepository extends Repository
{
    public function getAll()
    {
        $sql = "SELECT * FROM ProductSizes";
        try{
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            $stocks = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $stocks[] = $this->rowToStock($row);
            }

            return $stocks;
        }catch (PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    public function getAllHistory(){
        $sql = "SELECT StockHistory.history_id, Products.product_id, Products.name, Sizes.size_id, Sizes.size_name, StockHistory.quantity_changed, StockHistory.change_date, StockHistory.reason, StockHistory.stock_movement FROM StockHistory
                JOIN Products ON Products.product_id = StockHistory.product_id
                JOIN Sizes ON Sizes.size_id = StockHistory.size_id;";
        try{
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            $stockHis = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $stockHis[] = $this->rowToStockHistory($row);
            }

            return $stockHis;
        }catch (PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    public function insert($stock)
    {
        try{
            $productSizesQuery = "INSERT INTO ProductSizes (product_id, size_id, stock_quantity ) VALUES (?,?,?)";
            $stmt = $this->connection->prepare($productSizesQuery);
            $stmt->execute([
                $stock->product->id,
                $stock->size->size_id,
                $stock->stock_quantity
            ]);
            $productId = $this->connection->lastInsertId();

            if($stmt->rowCount() > 0){
                if($this->insertIntoStockHistory($stock)){
                    return $productId;
                }
            }else{
                return false;
            }
        }catch (PDOException $e){
            throw new Exception($e->getMessage());
        }
    }


    public function update($stock)
    {
        try{

            $productSizesQuery = "UPDATE ProductSizes SET 
            stock_quantity = CASE
                WHEN :stock_movement = 'IN' THEN stock_quantity + :stock_quantity
                WHEN :stock_movement = 'OUT' AND stock_quantity >= :stock_quantity THEN stock_quantity - :stock_quantity
                ELSE stock_quantity
            END
            WHERE product_id = :product_id AND size_id = :size_id";

            $stmt = $this->connection->prepare($productSizesQuery);
            $stmt->execute([
                ':product_id' => $stock->product->id,
                ':size_id' => $stock->size->size_id,
                ':stock_quantity' => $stock->stock_quantity,
                ':stock_movement' => $stock->stock_movement
            ]);


            if($stmt->rowCount() > 0){
                return $this->insertIntoStockHistory($stock);
            }else{
                return false;
            }
        }catch (PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    private function insertIntoStockHistory($stock){
        try {

            $stockHistoryQuery = "INSERT INTO StockHistory (product_id, size_id, quantity_changed, reason, stock_movement) VALUES (?,?,?,?,?)";
            $stmt = $this->connection->prepare($stockHistoryQuery);
            $stmt->execute([
                $stock->product->id,
                $stock->size->size_id,
                $stock->stock_quantity,
                $stock->reason,
                $stock->stock_movement
            ]);

            return $this->connection->lastInsertId();
        }catch (PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    public function rowToStock($row){

        $stock = new Stock();
        $stock->stock_quantity = $row['stock_quantity'];
        $product = new Product();
        $product->id = $row['product_id'];
        $size = new Size();
        $size->size_id = $row['size_id'];

        $stock->size = $size;
        $stock->product = $product;

        return $stock;
    }

    public function rowToStockHistory($row){

        $stockHis = new StockHistory();
        $stockHis->history_id = $row['history_id'];
        $stockHis->quantity_changed = $row['quantity_changed'];
        $stockHis->change_date = new DateTime($row['change_date']);
        $stockHis->reason = $row['reason'];
        $stockHis->stock_movement = $row['stock_movement'];

        $product = new Product();
        $product->id = $row['product_id'];
        $product->name = $row['name'];

        $size = new Size();
        $size->size_id = $row['size_id'];
        $size->size_name = $row['size_name'];

        $stockHis->product = $product;
        $stockHis->size = $size;

        return $stockHis;
    }
}
