<?php
namespace Services;

use Repositories\StockRepository;

class StockService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new StockRepository();
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getAllHistory(){
        return $this->repository->getAllHistory();
    }
    public function getOne($id)
    {
        return $this->repository->getOne($id);
    }

    public function insert($item)
    {
        return $this->repository->insert($item);
    }

    public function update($item)
    {
        return $this->repository->update($item);
    }

    public function delete($item)
    {
        return $this->repository->delete($item);
    }
}
