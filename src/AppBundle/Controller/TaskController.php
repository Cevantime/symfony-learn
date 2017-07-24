<?php


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Task;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\TaskType;

/**
 * Description of TaskController
 *
 * @author cevantime
 */
class TaskController extends Controller {
	
	/**
	 * @Route("/tasks", name="app_task_index")
	 */
	public function indexAction() {
		return $this->render('tasks/index.html.twig');
	}
	
	/**
	 * @Route("task/add",name="app_task_add")
	 * @param Request $request
	 */
	public function addAction(Request $request) {
		
		$task = new Task();
		
//		$task->setTask('Learn Symfony');
		$task->setDueDate(new \DateTime('tomorrow'));
		
		$form = $this->createForm(TaskType::class)
			->add('submit', SubmitType::class, array('label'=>'Ajouter une tâche'));
		
		$form->handleRequest($request);
		
		if($form->isSubmitted() && $form->isValid()) {
			
		}
		
		return $this->render('tasks/add.html.twig', ['formAdd' => $form->createView()]);
	}
}
