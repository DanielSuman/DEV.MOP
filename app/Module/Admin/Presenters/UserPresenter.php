<?php
declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use Nette;
use App\Model\UserFacade;
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

    public function createComponentEditForm() {

        $form = new Nette\Application\UI\Form;
        $form->addText('username', 'Uživatelské Jméno')
            ->setRequired('Zadejte uživatelské jméno.');
        $form->addTExt('email', 'Email')
            ->setRequired('Zadejte email.');
        $form->addText('password', 'Heslo');
        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = [$this, 'editFormSucceeded'];

        $existingUser = $this->userFacade->getById($this->user->id);

        bdump($existingUser);

        if($existingUser !== null) {
            $formData = $existingUser->toArray();

            // Don't wish to fill the password in automatically
            unset($formData['password']);
            $form->setDefaults($formData);
        }
        bdump($existingUser);

        return $form;
    }
    public function editFormSucceeded($form, $values) {
        // aktuálně přihlášený uživatel
        $this->userFacade->edit($this->user->id, $values);
    }
    public function renderEdit(): void {
        
    }
    
    public function handleDelete(int $userId) {
        $this->userFacade->delete($userId);
        $this->redirect('User:default');
    }
}
