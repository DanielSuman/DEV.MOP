<?php 

declare(strict_types=1);

namespace App\Model;

use Nette;

final class ModFacade
{
    use Nette\SmartObject;
    private Nette\Database\Explorer $database;
    public function __construct(Nette\Database\Explorer $database)
    {
        $this->database = $database;
    }
    public function getPublishedMods()
    {
        return $this->database
        ->table("mods")
        ->where("created_at < ", new \DateTime)
        ->order("created_at DESC");
    }
    public function getModsBySearchWord($searchWord)
    {
        return $this->database->table('posts')->where('text LIKE ?', "%$searchWord%")->fetchAll();
    }

}