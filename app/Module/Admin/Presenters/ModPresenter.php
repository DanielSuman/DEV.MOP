<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;


use Nette;
use Nette\Application\UI\Form;
use App\Model\ModFacade;


final class ModPresenter extends BasePresenter
{
	public function __construct(private ModFacade $modFacade){}
	
	public function renderShow(int $modId) 
	{
		$mod = $this->modFacade->getModById($modId);
		if (!$mod) {
			$this->error('Mod not found');
		}

		$this->template->mod = $mod;
	#	$this->template->comments = $mod->related('comment')->order('created_at');
	}
	public function renderResults(string $searchWord)
	{
		$this->template->results = $this->modFacade->getModsBySearchWord($searchWord);
	}

/*
	protected function createComponentCommentForm(): Form
	{
		$form = new Form;
		$form->addText('name', 'Your name:')
			->setRequired();

		$form->addEmail('email', 'Email:');

		$form->addTextArea('content', 'Comment:')
			->setRequired();

		$form->addSubmit('send', 'Publish comment');
		$form->onSuccess[] = [$this, 'commentFormSucceeded'];

		return $form;
	}


	public function commentFormSucceeded(\stdClass $data): void
	{
		$this->database->table('comments')->insert([
			'post_id' => $this->getParameter('postId'),
			'name' => $data->name,
			'email' => $data->email,
			'content' => $data->content,
		]);

		$this->flashMessage('Thank you for your comment', 'success');
		$this->redirect('this');
	} */
}