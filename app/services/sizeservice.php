<?php
namespace Services;

use Repositories\SizeRepository;

class SizeService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new SizeRepository();
    }

    public function getAll()
    {
        return $this->repository->getAll();
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

    public function sizesForCategory($id){
        return $this->repository->sizesForCategory($id);
    }
}

