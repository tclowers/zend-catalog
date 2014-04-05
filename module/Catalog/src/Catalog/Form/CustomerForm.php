<?php
//CustomerForm.php
namespace Catalog\Form;

use Zend\Form\Form;

class CustomerForm extends Form {

	public function __construct($name = null) {
		//we want to ignore the name passed
		parent::__construct('customer');

		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',
		));

		$this->add(array(
			'name' => 'name',
			'type' => 'Text',
			'options' => array(
				'label' => 'Name',
			),
		));

		$this->add(array(
			'name' => 'address1',
			'type' => 'Text',
			'options' => array(
				'label' => 'Street Address',
			),
		));

		$this->add(array(
			'name' => 'address2',
			'type' => 'Text',
			'options' => array(
				'label' => ' ',
			),
		));

		$this->add(array(
			'name' => 'city',
			'type' => 'Text',
			'options' => array(
				'label' => 'City',
			),
		));

		$this->add(array(
			'name' => 'state',
			'type' => 'Text',
			'options' => array(
				'label' => 'State',
			),
		));

		$this->add(array(
			'name' => 'zip',
			'type' => 'Text',
			'options' => array(
				'label' => 'Zip',
			),
		));

		$this->add(array(
			'name' => 'phone',
			'type' => 'Text',
			'options' => array(
				'label' => 'Phone',
			),
		));

		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'options' => array(
				'value' => 'Go',
				'id' => 'submitbutton',
			),
		));
	}
}