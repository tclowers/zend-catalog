<?php
//AlbumForm.php
namespace Catalog\Form;

use Zend\Form\Form;

class ProductForm extends Form {

	public function __construct($name = null) {
		//we want to ignore the name passed
		parent::__construct('product');

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
			'name' => 'description',
			'type' => 'Textarea',
			'options' => array(
				'label' => 'Description',
			),
		));

		$this->add(array(
			'name' => 'width',
			'type' => 'Text',
			'options' => array(
				'label' => 'Width',
			),
		));

		$this->add(array(
			'name' => 'length',
			'type' => 'Text',
			'options' => array(
				'label' => 'Length',
			),
		));

		$this->add(array(
			'name' => 'height',
			'type' => 'Text',
			'options' => array(
				'label' => 'Height',
			),
		));

		$this->add(array(
			'name' => 'weight',
			'type' => 'Text',
			'options' => array(
				'label' => 'Weight',
			),
		));

		$this->add(array(
			'name' => 'dollarValue',
			'type' => 'Text',
			'options' => array(
				'label' => 'Dollar Value',
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