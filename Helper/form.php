<?php
	class Pivotal_Form{
		var $ds = DIRECTORY_SEPARATOR;
		var $_classNames = array(
			'container' => 'payment-container',
			'wrapper' => 'card-wrapper',
			'formContainer' => 'form-container active',
			'button' => 'expand radius',
			);

		var $_formAction = '';
		var $_buttonText = 'Submit';

		public function __construct($formAction =''){
			$this->_formAction = $formAction;
		}

		public function buildForm(){
			$out = '';
			$out .= $this->loadCSS();
			$out .= $this->loadJS();
			$out .= $this->container();
			$out .= $this->footerScript();

			return $out;
		}

		public function loadCSS(){
			$ds = $this->ds;
			$out = '<style>'.file_get_contents(dirname(dirname(__FILE__)).$ds.'www'.$ds.'card-master'.$ds.'lib'.$ds.'css'.$ds.'card.css').'</style>';
			$out .= '<style>
					.'.$this->_classNames['container'].' {
						width: 100%;
						max-width: 350px;
						margin: 50px auto;
					}
					form {
						margin: 30px;
					}
					input {
						width: 200px;
						margin: 10px auto;
						display: block;
					}
				</style>';
			return $out;
		}

		public function loadJS(){
			$ds =$this->ds;
			return '<script>'.file_get_contents(dirname(dirname(__FILE__)).$ds.'www'.$ds.'card-master'.$ds.'lib'.$ds.'js'.$ds.'card.js').'</script>';
		}

		public function setClassNames($classNames = array()){
			$this->_classNames = $classNames + $this->_classNames;
		}

		public function setAction($action = ''){
			$this->_formAction = $formAction;
		}

		public function setButtonText($text = 'Submit'){
			$this->_buttonText = $text;
		}
		
		public function container(){
			$out = '<div class="'.$this->_classNames['container'].'">';
				$out .= $this->wrapper();
				$out .= $this->formContainer();
			$out .= '</div>';
			return $out;
		}

		public function wrapper(){
			$out = '<div class="'.$this->_classNames['wrapper'].'"></div>';
			return $out ;

		}

		public function formContainer(){
			$out = '';
			$out .= '<div class="'.$this->_classNames['formContainer'].'">';
			$out .= $this->form();
			$out .= '</div>';
			return $out;
		}
		
		public function form(){
			$out = '<form action="'.$this->_formAction.'" method="POST">';
			$out .= $this->inputs();
			$out .= $this->button();
			$out .= '</form>';
			return $out;
		}

		public function inputs(){
			$out = '<input placeholder="Card number" type="text" name="CARDNUMBER">';
			$out .= '<input placeholder="Full name" type="text" name="CARDHOLDERNAME">';
			$out .= '<input placeholder="MM/YY" type="text" name="EXPIRY">';
			$out .= '<input placeholder="CVC" type="text" name="CVV">';
			return $out;
		}

		public function button(){
			$out = '<button type="submit" name="submit" class="'.$this->_classNames['button'].'" text="Submit">'.$this->_buttonText.'</button>';
			return $out;
		}

		public function footerScript(){
			$out = "<script>
				$('.active form').card({
					container: $('.card-wrapper'),
					numberInput: 'input[name=\"CARDNUMBER\"]',
					expiryInput: 'input[name=\"EXPIRY\"]',
					cvcInput: 'input[name=\"CVV\"]',
					nameInput: 'input[name=\"CARDHOLDERNAME\"]'
				})";
			$out .= '</script>';
			return $out;
		}


	}
?>