<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Task
 *
 * @author cevantime
 */
class Task {

	/**
	 * @Assert\NotBlank()
	 * @var string
	 */
	protected $task;
	
	/**
	 * @Assert\NotBlank()
	 * @Assert\Type(
	 *	type="\DateTime", 
	 *	message="{{ value }} is not a valid {{ type }}"
	 * )
	 * @var DateTime
	 */
	protected $dueDate;

	public function getTask() {
		return $this->task;
	}

	public function setTask($task) {
		$this->task = $task;
	}

	public function getDueDate() {
		return $this->dueDate;
	}

	public function setDueDate(\DateTime $dueDate = null) {
		$this->dueDate = $dueDate;
	}

}
