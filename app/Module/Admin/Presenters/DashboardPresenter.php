<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use Nette;


final class DashboardPresenter extends BasePresenter
{
	use RequireLoggedUser;

	public function actionWelcome()
	{

	}
}
