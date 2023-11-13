<?php 

declare (strict_types= 1);

namespace App\Module\Admin\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class SearchPresenter extends Nette\Application\UI\Presenter {
    private Nette\Database\Explorer $database;


	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}
    public function renderDefault(Form $form): void {

    }
    public function createComponentSearch(Form $form) {
        $form ->addText("result","Hledat...");
        $form ->addSubmit("submit","submit");
    }
    public function searchFormSucceeded(Form $form) {
        $result = $this->database->query("");
    } 
}
