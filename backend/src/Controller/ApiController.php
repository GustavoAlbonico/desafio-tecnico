<?php

namespace App\Controller;

use Cake\Event\EventInterface;

class ApiController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->request = $this->request->withParam('_ext', 'json');
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->setClassName('Json');
    }
}