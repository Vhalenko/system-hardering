<?php

require_once(__DIR__ . "/../models/ComicModel.php");

class ComicController
{
    private $comicModel;
    public function __construct()
    {
        $this->comicModel = new UserModel();
    }
}
