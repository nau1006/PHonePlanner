<?php
	declare(strict_types=1);

	class Contact {
		private $id;
		private $name;
		private $number;
		private $e_mail;
		
		public function __construct(int $id, string $name, string $number, string $e_mail) {
			$this->id = $id;
			$this->name = $name;
			$this->number = $number;
			$this->e_mail = $e_mail;
		}

		public function getId() {return $this->id;}
		public function getName() {return $this->name;}
		public function getNumber() {return $this->number;}
		public function getEmail() {return $this->e_mail;}
	}
?>