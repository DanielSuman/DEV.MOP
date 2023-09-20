<?php
declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use App\Model\UserFacade; // Import the UserFacade class
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;

class UserPresenter extends Presenter
{
    private UserFacade $userFacade; // Declare a private property for UserFacade

    // Inject UserFacade through the constructor
    public function __construct(UserFacade $userFacade)
    {
        parent::__construct();
        $this->userFacade = $userFacade;
    }

    public function renderDefault()
    {
        // Fetch all users using your UserFacade's getAll() method
        $users = $this->userFacade->getAll();

        // Pass the $users variable to the template
        $this->template->users = $users;
    }

    public function renderDetail(int $id)
    {
        // Fetch a specific user by ID using your UserFacade's getAll() method
        $user = $this->userFacade->get($id); // Assuming you have a get() method for specific user retrieval

        // Pass the $user variable to the template
        $this->template->user = $user;
    }

    public function createComponentEditForm(): Form {
        $form = new Form;
        $form->addText('username', 'Uživ. Jméno:')
		->setRequired();
	    $form->addTextArea('password', 'Heslo:')
		->setRequired();

    	$form->addSubmit('send', 'Uložit změny');
    	$form->onSuccess[] = $this->editFormSucceeded(...);

	    return $form;
    }
    public function editFormSucceeded(array $data): void 
    {
        $userItem = $this->userFacade->editUser($this->userItem->id, $data);
        
        $this->flashMessage("Změny byly uloženy", 'success');
	    $this->redirect('User:default');
    }
    public function renderEdit(): void {
        
    }
    
    public function handleDelete(int $userId) {
        $this->userFacade->delete($userId);
        $this->redirect('User:default');
    }
}
