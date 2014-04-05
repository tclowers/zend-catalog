<?php
//ProductController.php
namespace Catalog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Catalog\Form\ProductForm;
use Doctrine\ORM\EntityManager;
use Catalog\Entity\Product;


class ProductController extends AbstractActionController {

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
			'products' => $this->getEntityManager()->getRepository('Catalog\Entity\Product')->findAll(),
		));
	}

	public function showAction() {
		$id = (int) $this->params()->fromRoute('id', 0);
      if (!$id) {
          return $this->redirect()->toRoute('product');
      }

		//remember to add a show.phtml!!!
		return new ViewModel(array(
			'product' => $this->getEntityManager()->find('Catalog\Entity\Product', $id),
		));
	}

	public function addAction() {
		$form = new ProductForm();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if($request->isPost()) {
			//echo "ispost<br>";
			$product = new Product();
			$form->setInputFilter($product->getInputFilter());
			$form->setData($request->getPost());
			//echo "still running<br>";

			if($form->isValid()) {
				//echo "valid form<br>";
				//use doctrine2 to persist product in db
				$data = $form->getData();
				//foreach ($data as $key => $val) {
				//	echo " " . $key . ": " . $val . "<br>";
				//}

				$product->populate($data);
				//echo "name: " . $data['name'] . "<br>";
				//generate timestamp
				$product->lastUpdate = new \DateTime('now');
				$this->getEntityManager()->persist($product);
				$this->getEntityManager()->flush();

				//redirect to list of products
				return $this->redirect()->toRoute('product');
			}
		}
		return array('form' => $form);
	}

	public function editAction() {
	   $id = (int) $this->params()->fromRoute('id', 0);
	   if (!$id) {
	       return $this->redirect()->toRoute('product', array(
	           'action' => 'add'
	       ));
	   }

	   // Get the Product with the specified id.  An exception is thrown
	   // if it cannot be found, in which case go to the index page.
	   try {
	   	//get with doctrine2
	       $product = $this->getEntityManager()->find('Catalog\Entity\Product', $id);
	   }
	   catch (\Exception $ex) {
	       return $this->redirect()->toRoute('product', array(
	           'action' => 'index'
	       ));
	   }

	   $form  = new ProductForm();
	   $form->bind($product);
	   $form->get('submit')->setAttribute('value', 'Edit');

	   $request = $this->getRequest();
	   if ($request->isPost()) {
	       $form->setInputFilter($product->getInputFilter());
	       $form->setData($request->getPost());

	       if ($form->isValid()) {
	       		$form->bindValues(); //hmm...
               $this->getEntityManager()->flush();

	           // Redirect to list of products
	           return $this->redirect()->toRoute('product');
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
          return $this->redirect()->toRoute('product');
      }

      $request = $this->getRequest();
      if ($request->isPost()) {
          $del = $request->getPost('del', 'No');

          if ($del == 'Yes') {
              $id = (int) $request->getPost('id');
              $product = $this->getEntityManager()->find('Catalog\Entity\Product', $id);
                if ($product) {
                    $this->getEntityManager()->remove($product);
                    $this->getEntityManager()->flush();
                }
              //$this->getproductTable()->deleteproduct($id);
          }

          // Redirect to list of products
          return $this->redirect()->toRoute('product');
      }

      return array(
          'id'    => $id,
          'product' => $this->getEntityManager()->find('Catalog\Entity\Product', $id),
      );
	}
}