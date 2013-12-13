<?php

namespace Application\Controller;

use Loculus\MVC\Controller\DefaultController;

class IndexController extends DefaultController
{
    public function indexAction()
    {
        return $this->viewModel;
    }

    public function zf2Action()
    {
        return $this->viewModel;
    }

    public function notSupportedLocaleAction()
    {
        return $this->viewModel;
    }
}
