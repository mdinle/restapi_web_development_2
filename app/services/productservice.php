<?php
namespace Services;

use Repositories\ProductRepository;

class ProductService {

    private $repository;

    function __construct()
    {
        $this->repository = new ProductRepository();
    }

    public function getAll() {
        return $this->repository->getAll();
    }

    public function getOne($id) {
        return $this->repository->getOne($id);
    }

    public function insert($item) {       
        return $this->repository->insert($item);        
    }

    public function update($item) {
        return $this->repository->update($item);
    }

    public function delete($id) {
        return $this->repository->delete($id);
    }

    public function getDetailedProduct($id){
        return $this->repository->getDetailedProduct($id);
    }

    public function detailedProducts(){
        return $this->repository->getDetailedProducts();
    }
}

?>