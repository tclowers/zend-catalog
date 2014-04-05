<?php
//CustomerController.php
namespace Catalog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Catalog\Form\CustomerForm;
use Doctrine\ORM\EntityManager;
use Catalog\Entity\Customer;


class CustomerController extends AbstractActionController {

	protected $em; //doctrine2 entity manager

	public function setEntityManager(EntityManager $em) {
	  $this->em = $em;
	}

	public function getEntityManager() {
		if(null === $this->em) {
			$this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		}
		return $this->em;
	}

	public function indexAction() {
		return new ViewModel(array(
			'customers' => $this->getEntityManager()->getRepository('Catalog\Entity\Customer')->findAll(),
		));
	}

	public function showAction() {
		$id = (int) $this->params()->fromRoute('id', 0);
      if (!$id) {
          return $this->redirect()->toRoute('customer');
      }

		//remember to add a show.phtml!!!
		return new ViewModel(array(
			'customer' => $this->getEntityManager()->find('Catalog\Entity\Customer', $id),
		));
	}

	public function addAction() {
		$form = new CustomerForm();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if($request->isPost()) {
			//echo "ispost<br>";
			$customer = new Customer();
			$form->setInputFilter($customer->getInputFilter());
			$form->setData($request->getPost());
			//echo "still running<br>";

			if($form->isValid()) {
				//echo "valid form<br>";
				//use doctrine2 to persist customer in db
				$data = $form->getData();
				//foreach ($data as $key => $val) {
				//	echo " " . $key . ": " . $val . "<br>";
				//}

				$customer->populate($data);
				//echo "name: " . $data['name'] . "<br>";
				//generate timestamp
				$customer->lastUpdate = new \DateTime('now');
				$this->getEntityManager()->persist($customer);
				$this->getEntityManager()->flush();

				//redirect to list of customers
				return $this->redirect()->toRoute('customer');
			}
		}
		return array('form' => $form);
	}

	public function editAction() {
	   $id = (int) $this->params()->fromRoute('id', 0);
	   if (!$id) {
	       return $this->redirect()->toRoute('customer', array(
	           'action' => 'add'
	       ));
	   }

	   // Get the customer with the specified id.  An exception is thrown
	   // if it cannot be found, in which case go to the index page.
	   try {
	   	//get with doctrine2
	       $customer = $this->getEntityManager()->find('Catalog\Entity\Customer', $id);
	   }
	   catch (\Exception $ex) {
	       return $this->redirect()->toRoute('customer', array(
	           'action' => 'index'
	       ));
	   }

	   $form  = new CustomerForm();
	   $form->bind($customer);
	   $form->get('submit')->setAttribute('value', 'Edit');

	   $request = $this->getRequest();
	   if ($request->isPost()) {
	       $form->setInputFilter($customer->getInputFilter());
	       $form->setData($request->getPost());

	       if ($form->isValid()) {
	       		$form->bindValues(); //hmm...
               $this->getEntityManager()->flush();

	           // Redirect to list of customers
	           return $this->redirect()->toRoute('customer');
	       }
	   }

	   return array(
	       'id' => $id,
	       'form' => $form,
	   );
	}

	public function deleteAction() {
		$id = (int) $this->params()->fromRoute('id', 0);
      if (!$id) {
          return $this->redirect()->toRoute('customer');
      }

      $request = $this->getRequest();
      if ($request->isPost()) {
          $del = $request->getPost('del', 'No');

          if ($del == 'Yes') {
              $id = (int) $request->getPost('id');
              $customer = $this->getEntityManager()->find('Catalog\Entity\Customer', $id);
                if ($customer) {
                    $this->getEntityManager()->remove($customer);
                    $this->getEntityManager()->flush();
                }
              //$this->getcustomerTable()->deletecustomer($id);
          }

          // Redirect to list of customers
          return $this->redirect()->toRoute('customer');
      }

      return array(
          'id'    => $id,
          'customer' => $this->getEntityManager()->find('Catalog\Entity\Customer', $id),
      );
	}
}