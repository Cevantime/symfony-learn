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
	 * @Route("tasks", name="app_task_index")
	 */
	public function indexAction() {
        
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();
        
		return $this->render('tasks/index.html.twig', ['tasks' => $tasks]);
	}
	
	/**
	 * @Route("task/add",name="app_task_add")
	 */
	public function addAction(Request $request) {
        return $this->processTask($request);
	}
    
    /**
     * @Route("task/edit/{id}", name="app_task_edit", requirements={ "id" : "\d+" })
     */
    public function editAction(Request $request, Task $task){
        return $this->processTask($request, $task);
    }


    private function processTask(Request $request, Task $task = null){
        
        $submitLabel = 'Edit';
        $responseVerb = 'updated';
        
        if(null === $task) {
            $task = new Task();
            $task->setDueDate(new \DateTime('tomorrow'));
            $submitLabel = 'Add';
            $responseVerb = 'added';
        }
		
		$form = $this->createForm(TaskType::class, $task)
			->add('submit', SubmitType::class, array(
                'label'=> $submitLabel
            ));
		
		$form->handleRequest($request);
		
		if($form->isSubmitted() && $form->isValid()) {
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            
            $this->addFlash('success', 'The task has been '.$responseVerb);
            
            return $this->redirectToRoute('app_task_index');
		}
		
		return $this->render('tasks/edition.html.twig', [ 
            'editionLabel' => $submitLabel, 
            'formTask' => $form->createView()
        ]);
    }
    /**
     * @Route("task/delete/{id}", name="app_task_delete", requirements={ "id" : "\d+" })
     */
    public function deleteAction(Task $task) {
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();
        
        $this->addFlash('success', 'The task has been successfully removed');
        
        return $this->redirectToRoute('app_task_index');
    }
}
