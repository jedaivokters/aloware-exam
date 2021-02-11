<?php

namespace App\Repositories;

use App\Blog;
use App\Repositories\Interfaces\BlogRepositoryInterface;
use Illuminate\Support\Collection;

class BlogRepository extends BaseRepository implements BlogRepositoryInterface
{

   /**
    * UserRepository constructor.
    *
    * @param User $model
    */
   public function __construct(Blog $model)
   {
       parent::__construct($model);
   }

   /**
    * @return Collection
    */
   public function all(): Collection
   {
       return $this->model->all();    
   }
}