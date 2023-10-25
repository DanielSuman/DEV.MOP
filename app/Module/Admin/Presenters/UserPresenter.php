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
    public function actionDetail(int $id) {
        if(!$this->user->isInRole('admin')) {
            $this->flashMessage('Nemáte právo editovat uživatele!', 'danger');
            $this->redirect('User:default');
        }
    }
    public function renderDetail(int $id)
    {
        // Fetch a specific user by ID using your UserFacade's getAll() method
        $person = $this->userFacade->getById($id); // Assuming you have a get() method for specific user retrieval

        // Pass the $user variable to the template
        $this->template->person = $person;
    }
    public function createComponentEditForm() {

        $form = new Nette\Application\UI\Form;
        $form->addUpload('image', 'Upload a Profile Image')
            ->addRule(Form::IMAGE, 'Thumbnail must be JPEG, PNG or GIF');
        $form->addText('username', 'Uživatelské Jméno')
            ->setRequired('Zadejte uživatelské jméno.');
        $form->addText('email', 'Email')
            ->setRequired('Zadejte email.');
        $form->addPassword('password', 'Heslo');

        if($this->user->isInRole('admin')) {
            $select = $form->addSelect(
                'role',
                'Role',
                [
                    'admin' => 'Admin',
                    'user' => 'User',
                ]
            );
        }

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = [$this, 'editFormSucceeded'];

        $id = $this->getParameter('id');

        $existingUser = $this->userFacade->getById((int) $id);

        bdump($existingUser);

        if($existingUser !== null) {
            $formData = $existingUser->toArray();

            // Don't wish to fill the password in automatically
            unset($formData['password']);

            $form->setDefaults($formData);
            $select->setDefaultValue($formData['role']);
        }
        bdump($existingUser);

        return $form;
    }
    public function editFormSucceeded($form, $values) {

        $id = $this->getParameter('id');
        // Změna hesla, pokud není řádek prázdný (Neměnit, pokud je vstupní okénko prázdné)
        if($values->password !== '') {
            $this->userFacade->changePassword((int) $id, $values->password);
        }

        unset($values->password);

         // Informuje, že uživ. jméno je obsazeno, pokud to není jméno aktuálně přihlášeného uživatele.
        if ($values->username == $this->user->getIdentity()->username) {


            unset($values->username);
        } elseif (
            ($this->userFacade->getByUserName($values->username) !== null)
            && ($this->userFacade->getById((int) $id)->username != $values->username)
        ) {
            $this->flashMessage('Uživatelské jméno je již zabrané.', 'danger');
            $this->redirect('this');
        }

        bdump($values->image);
        // Profil
            if ($values->image->isOk()) {
                $values->image->move('upload/' . $this->userFacade->getById((int) $id) . '/' . $values->image->getSanitizedName());
                $values['image'] = ('upload/' . $this->userFacade->getById((int) $id) . '/' . $values->image->getSanitizedName());
                echo '<img src="' . $values['image'] . '" alt="Uploaded Image">';

        } else {
            unset($values->image);
            $this->flashMessage('Soubor nebyl přidán', 'failed');
            // $this->redirect('this');
        }

        // aktuálně přihlášený uživatel
        bdump($this->user);
        $this->userFacade->edit((int) $id, $values);
    }
    public function renderEdit(): void {
        
    }


    // Odstranění uživ. profilové fotky
    public function handleDeleteImage(int $userId) {
        $uawe = $this->userFacade->getById($userId);
    }
    // Odstranění uživatele
    public function handleDelete(int $userId) {
        $this->userFacade->delete($userId);
        $this->redirect('User:default');
    }
}
