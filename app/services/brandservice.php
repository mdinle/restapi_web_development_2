<?php
namespace Services;

use Repositories\BrandRepository;

class BrandService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new BrandRepository();
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
}
