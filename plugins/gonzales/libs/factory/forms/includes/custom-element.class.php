<?php
	/**
	 * The file contains the base class for all custom elements.
	 *
	 * @author Alex Kovalev <alex.kovalevv@gmail.com>
	 * @copyright (c) 2018, Webcraftic Ltd
	 *
	 * @package factory-forms
	 * @since 1.0.0
	 */

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	if( !class_exists('Wbcr_FactoryForms0c0d8ea3f46097b6066de1bed10ef3d9_453_CustomElement') ) {
		/**
		 * The base class for all controls.
		 *
		 * @since 1.0.0
		 */
		abstract class Wbcr_FactoryForms0c0d8ea3f46097b6066de1bed10ef3d9_453_CustomElement extends Wbcr_FactoryForms0c0d8ea3f46097b6066de1bed10ef3d9_453_FormElement {

			/**
			 * Is this element a custom form element?
			 *
			 * @since 1.0.0
			 * @var bool
			 */
			public $is_custom = true;

			public function render()
			{

				// if the control is off, then ignore it
				$off = $this->getOption('off', false);

				if( $off ) {
					return;
				}

				$this->html();
			}
		}
	}