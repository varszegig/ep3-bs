<?php

namespace Frontend\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {

        $serviceManager = @$this->getServiceLocator();
        $calendarViewModel = $this->forward()->dispatch('Calendar\Controller\Calendar', ['action' => 'index']);
        $calendarViewModel->setCaptureTo('calendar');
        $squareManager = $serviceManager->get('Square\Manager\SquareManager');

        $group = $this->determineSquareGroup($squareManager);
       
        $dateStart = $calendarViewModel->getVariable('dateStart');
        $dateNow = $calendarViewModel->getVariable('dateNow');
        $squaresFilter = $calendarViewModel->getVariable('squaresFilter');
        $user = $calendarViewModel->getVariable('user');
        // $squareGroup = $calendarViewModel->getVariable('group-select');

        $this->redirectBack()->setOrigin('frontend');

        $viewModel = new ViewModel(array(
            'dateStart' => $dateStart,
            'dateNow' => $dateNow,
            'squaresFilter' => $squaresFilter,
            'user' => $user,
            'groupSelect' => $group,
        ));

        $viewModel->addChild($calendarViewModel);

        return $viewModel;
    }

}
