<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use App\Model\ModFacade;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
	private ModFacade $facade;


	public function __construct(ModFacade $facade)
	{
		$this->facade = $facade;
	}
	public function renderDefault(): void
	{
		$this->template->mods = $this->facade
			->getPublishedMods()
			->limit(5);
	}
}