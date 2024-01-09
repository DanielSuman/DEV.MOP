<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use Nette;
use Nette\Application\UI\Form;


abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	public function createComponentSearchModForm() {
        $form = new Form;
        $form->addText('searchWord', 'Search');
        $form->addSubmit('submit', 'Search');
        $form->onSuccess[]=$this->searchModFormSucceed(...);
        return $form;
    }
    public function searchModFormSucceed(\stdClass $data): void 
    {
        bdump($data);
        $this->flashMessage('Showing results...', 'success');
		$this->redirect('Mod:results');
    }
}
