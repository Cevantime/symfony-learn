<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\Task;

class TaskControllerTest extends WebTestCase
{
    public function testIndex() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tasks');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Tasks List', $crawler->filter('#container h1')->text());
        
        $addTaskLink = $crawler->filter('#container table ~ a.btn.btn-default');
        $this->assertContains('Add a task', $addTaskLink->text());
        
    }
    
    public function testNavigateToAdd() {
         $client = static::createClient();

        $crawler = $client->request('GET', '/tasks');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Tasks List', $crawler->filter('#container h1')->text());
        
        $addTaskLink = $crawler->filter('#container table ~ a.btn.btn-default');
        $this->assertContains('Add a task', $addTaskLink->text());
        
        $crawler = $client->click($addTaskLink->link());
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Add a task', $crawler->filter('#container h1')->text());
    }
    
    public function testAdd() {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/task/add');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Add a task', $crawler->filter('#container h1')->text());
        
        $formTraversable = $crawler->filter('form[name="task"]');
        $this->assertEquals( $formTraversable->count(), 1);
        
        $form = $formTraversable->form();
        
        $taskContent = 'New test task n°'.(md5(uniqid()));
        $form['task[task]'] = $taskContent;
        $form['task[dueDate][year]'] = '2017';
        $form['task[dueDate][month]'] = '8';
        $form['task[dueDate][day]'] = '10';
        
        $crawler = $client->submit($form);
        
        $this->assertEquals(true, $client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains($taskContent, $crawler->filter('#container table')->text());
        
        $doctrine = $client->getContainer()->get('doctrine');

        $task = $doctrine->getRepository(Task::class)
                ->findOneBy(array('task'=>$taskContent));
               
        $em = $doctrine->getManager();
        $em->remove($task);
        $em->flush();
    }
    
    public function testEdit() {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/task/add');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Add a task', $crawler->filter('#container h1')->text());
        
        $formTraversable = $crawler->filter('form[name="task"]');
        $this->assertEquals( $formTraversable->count(), 1);
        
        $form = $formTraversable->form();
        
        $taskContent = 'New test task n°'.(md5(uniqid()));
        $form['task[task]'] = $taskContent;
        $form['task[dueDate][year]'] = '2017';
        $form['task[dueDate][month]'] = '8';
        $form['task[dueDate][day]'] = '10';
        
        $crawler = $client->submit($form);
        
        $this->assertEquals(true, $client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains($taskContent, $crawler->filter('#container table')->text());
        
        $linkEdit = $crawler->filter('#container table tbody tr:last-child a.btn-default')
                ->link();
        
        $crawler = $client->click($linkEdit);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Edit a task', $crawler->filter('#container h1')->text());
        
        $formTraversable = $crawler->filter('form[name="task"]');
        $this->assertEquals( $formTraversable->count(), 1);
        
        $form = $formTraversable->form();
        
        $newTaskContent = 'Updated test task n°'.(md5(uniqid()));
        $form['task[task]'] = $newTaskContent;
        
        $crawler = $client->submit($form);
        
        $this->assertEquals(true, $client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains($newTaskContent, $crawler->filter('#container table')->text());
        
        $doctrine = $client->getContainer()->get('doctrine');

        $task = $doctrine->getRepository(Task::class)
                ->findOneBy(array('task'=>$newTaskContent));
               
        $em = $doctrine->getManager();
        $em->remove($task);
        $em->flush();
    }
}
