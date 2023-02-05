<?php
	require_once("Contact.php");

	class ContactHandler {
		private static $instance = null;
		private $fileName;
		private $fileStream;
		private $contacts;

		private function __construct() {
			$this->fileName = "contacts.json";
			$this->fileStream = fopen($this->fileName, "r+") or die("Unable to find or open $this->fileName");
			$GLOBALS["contactsData"] =  json_decode(fread($this->fileStream, filesize($this->fileName)), true);
			$this->contacts = array_map(
				array: $GLOBALS["contactsData"],
				callback: function($contact) {
					$contactID = array_search($contact, $GLOBALS["contactsData"]);
					return new Contact(
						$contactID,
						$contact["name"],
						$contact["number"],
						$contact["e_mail"]
					);
				}
			);
		}

		public function getContacts() {return $this->contacts;}

		public function addContact(Contact $newContact): bool {
			array_push($this->contacts, $newContact);
			return $this->updateData($this->generateArray());
		}

		public function updateContact(Contact $updatedContact): bool {
			$this->contacts[$updatedContact->getId()] = $updatedContact;
			return $this->updateData($this->generateArray());
		}

		public function removeContact(Contact $oldContact): bool {
			$GLOBALS["oldContactId"] = $oldContact->getId();
			$contactValues = array_filter(
				array: $this->contacts,
				callback: function($contact) {
					return $contact->getId() != $GLOBALS["oldContactId"];
				}
			);
			$numberOfContacts = count($contactValues);
			$contactKeys = range(0, $numberOfContacts - 1, 1);
			$this->contacts = $numberOfContacts < 1? [] : array_combine($contactKeys, $contactValues);
			return $this->updateData($this->generateArray());
		}

		public function getTotalContacts(): int {return count($this->contacts);}

		private function updateData(array $data): bool {
			ftruncate($this->fileStream, 0);
			fseek($this->fileStream, 0);
			fwrite($this->fileStream, json_encode($data));
			return fflush($this->fileStream);
		}

		private function generateArray(): array {
			return array_map(
				array: $this->contacts,
				callback: function(Contact $contact) {
					return array(
						"name"=>$contact->getName(),
						"number"=>$contact->getNumber(),
						"e_mail"=>$contact->getEmail()
					);
				}
			);
		}

		static function getInstance(): ContactHandler {
			self::$instance = self::$instance == null? new ContactHandler():self::$instance;
			return self::$instance;
		}

		public function __destruct() {fclose($this->fileStream);}
	}