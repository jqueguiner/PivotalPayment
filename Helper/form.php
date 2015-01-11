<?php
	class Pivotal_Form{
		var $ds = DIRECTORY_SEPARATOR;
		
		var $_classNames = array(
			'container' => 'payment-container',
			'wrapper' => 'card-wrapper',
			'formContainer' => 'form-container active',
			'button' => 'expand radius',
			);

		var $_inputNames = array(
			'numberInput' => 'CARDNUMBER',
			'expiryInput' => 'EXPIRY',
			'cvcInput' => 'CVV',
			'nameInput' => 'CARDHOLDERNAME'
			);
		
		var $_formAction = '';
		
		var $_formId = 'myPaymentForm';
		
		var $_buttonText = 'Submit';

		public function __construct($formAction = '', $formId = 'myPaymentForm'){
			$this->_formAction = $formAction;
			$this->_formId = $formId;			
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
			$out = '';
			$out .= '<style>'.file_get_contents(dirname(dirname(__FILE__)).$ds.'www'.$ds.'card-master'.$ds.'lib'.$ds.'css'.$ds.'card.css').'</style>';
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

		public function setInputNames($inputNames = array()){
			$this->_inputNames = $inputNames + $this->_inputNames;
		}

		public function setAction($action = ''){
			$this->_formAction = $formAction;
		}

		public function setButtonText($text = 'Submit'){
			$this->_buttonText = $text;
		}
		
		public function container(){
			$out = '';
			$out .= '<div class="'.$this->_classNames['container'].'">';
				$out .= $this->wrapper();
				$out .= $this->formContainer();
			$out .= '</div>';
			return $out;
		}

		public function wrapper(){
			$out = '';
			$out .= '<div class="'.$this->_classNames['wrapper'].'"></div>';
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
			$out = '';
			$out .= '<form id="'.$this->_formId.'" action="'.$this->_formAction.'" method="POST">';
			$out .= $this->inputs();
			$out .= $this->button();
			$out .= '</form>';
			return $out;
		}

		public function inputs(){
			$out = '';
			$out .= '<input placeholder="Card number" type="text" name="CARDNUMBER" required>';
			$out .= '<input placeholder="Full name" type="text" name="CARDHOLDERNAME" required>';
			$out .= '<input placeholder="MM/YY" type="text" name="EXPIRY" required>';
			$out .= '<input placeholder="CVC" type="text" name="CVV" required>';
			return $out;
		}

		public function button(){
			$out = '';
			$out .= '<button type="submit" name="submit" class="'.$this->_classNames['button'].'" text="Submit">'.$this->_buttonText.'</button>';
			return $out;
		}

		public function footerScript(){

			$out = '';
			$out .= '<script>';
			$out .= "
				$('.active form').card({
					container: $('.".$this->_classNames['wrapper']."'),
					numberInput: 'input[name=\"".$this->_inputNames['numberInput']."\"]',
					expiryInput: 'input[name=\"".$this->_inputNames['expiryInput']."\"]',
					cvcInput: 'input[name=\"".$this->_inputNames['cvcInput']."\"]',
					nameInput: 'input[name=\"".$this->_inputNames['nameInput']."\"]'
				})";

			$out .= "
				var regexNumber = /^[0-9 ]{13,23}$/;
				var regexExpiry = /^[0-1]{1}[0-9]{1} \/ [1-2]{1}[0-9]{1}$/;
				var regexCVC = /^[0-9]{3,4}$/;
				var regexName = /.+/;

				var inputNumber = 'input[name=\"".$this->_inputNames['numberInput']."\"]';
				var inputExpiry = 'input[name=\"".$this->_inputNames['expiryInput']."\"]';
				var inputCVC = 'input[name=\"".$this->_inputNames['cvcInput']."\"]';
				var inputName = 'input[name=\"".$this->_inputNames['nameInput']."\"]';

				$(\"#".$this->_formId."\").submit(function(event) {

					if(!$(inputNumber).val() || !regexNumber.test($(inputNumber).val())){
						alert('Card number can\'t be empty or incorrect format (min lenght : 10 digits)');
						return false;
						event.preventDefault();
					}else if (!$(inputExpiry).val() || !regexExpiry.test($(inputExpiry).val())){
						alert('Expiry date can\'t be empty or incorrect format (MM/YY)');
						return false;
						event.preventDefault();
					}else if (!$(inputCVC).val() || !regexCVC.test($(inputCVC).val())){
						alert('CVC can\'t be empty or incorrect format (3 or 4 digits)');
						return false;
						event.preventDefault();
					}else if (!$(inputName).val() || !regexName.test($(inputName).val())){
						alert('Name can\'t be empty');
						return false;
						event.preventDefault();
					}
				});";

			$out .= '</script>';
			return $out;
		}


	}
?>