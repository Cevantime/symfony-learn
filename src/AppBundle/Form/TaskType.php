<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use AppBundle\Entity\Task;

/**
 * Description of TaskType
 *
 * @author cevantime
 */
class TaskType extends AbstractType {
	
	public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options) {
		$builder
			->add('task', TextType::class)
			->add('dueDate', DateType::class, array(
                'invalid_message' => "{{ value }} is not a valid date time"
            ));
	}
	
	public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => Task::class
		));
	}
}
