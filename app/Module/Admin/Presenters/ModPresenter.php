<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use Nette;
use Nette\Application\UI\Form;


final class ModPresenter extends Nette\Application\UI\Presenter
{
	private Nette\Database\Explorer $database;


	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}


	public function renderShow(int $modId): void
	{
		$mod = $this->database->table('mods')->get($modId);
		if (!$mod) {
			$this->error('Mod not found');
		}

		$this->template->mod = $mod;
	#	$this->template->comments = $mod->related('comment')->order('created_at');
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