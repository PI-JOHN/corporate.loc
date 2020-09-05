<?php


namespace App\Repositories;



use App\Articles;

class ArticlesRepository extends Repository
{
    public function __construct(Articles $article)
    {
        $this->model = $article;
    }
}
