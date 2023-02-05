<!DOCTYPE html>
<html lang="en" oncontextmenu="return false;">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PHonePlanner</title>
	<!-- UIKit CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.15.22/dist/css/uikit.min.css" />
	<!-- UIKit JS -->
	<script src="https://cdn.jsdelivr.net/npm/uikit@3.15.22/dist/js/uikit.min.js" defer></script>
	<script src="https://cdn.jsdelivr.net/npm/uikit@3.15.22/dist/js/uikit-icons.min.js" defer></script>
</head>

<?php
require_once("ContactHandler.php");
require_once("Contact.php");

define("SELF_PATH", htmlspecialchars($_SERVER["PHP_SELF"]));
define("CONTACT_HANDLER", ContactHandler::getInstance());

if (isset($_POST["action"])) {
	define("ACTION_CONTACT", new Contact(
		$_POST["id"],
		$_POST["name"],
		$_POST["number"],
		empty($_POST["e_mail"]) ? "No e-mail registered" : $_POST["e_mail"]
	));

	switch ($_POST["action"]) {
		case "create":
			CONTACT_HANDLER->addContact(ACTION_CONTACT);
			break;
		case "update":
			CONTACT_HANDLER->updateContact(ACTION_CONTACT);
			break;
		case "delete":
			CONTACT_HANDLER->removeContact(ACTION_CONTACT);
			break;
		default:;
	}
}
?>

<body class="uk-background-default uk-padding-large uk-padding-remove-bottom">
	<header class="uk-animation-slide-top uk-margin-large">
		<h1 class="uk-heading-medium uk-text-primary uk-position-relative uk-position-top-center">
			PHonePlanner
		</h1>
	</header>
	<main class="uk-flex-column">
		<section uk-accordion class="uk-margin-large uk-animation-slide-top">
			<article class="uk-background-primary uk-width-xlarge uk-align-center">
				<a class="uk-accordion-title uk-padding-small">New Contact</a>
				<form action="<?php echo SELF_PATH; ?>" method="post" class="uk-accordion-content uk-padding-large uk-padding-remove-top uk-form-stacked">
					<label for="name" class="uk-form-label">Name</label>
					<input class="uk-input uk-form-width-large uk-margin-bottom" type=" text" name="name" id="name" required>
					<label for="number" class="uk-form-label">Phone Number</label>
					<input class="uk-input uk-form-width-large uk-margin-bottom" type=" tel" name="number" id="number" required>
					<label for="e-mail" class="uk-form-label">E-mail</label>
					<input class="uk-input uk-form-width-large" type="email" name="e_mail" id="e_mail">
					<input type="hidden" name="id" id="id" value="<?php echo CONTACT_HANDLER->getTotalContacts(); ?>">
					<button type="submit" name="action" id="action" value="create" class="uk-button uk-button-text uk-align-center">
						<span uk-icon="icon: upload; ratio: 2"></span>
					</button>
				</form>
			</article>
		</section>
		<section>
			<ul uk-accordion="multiple: true" uk-scrollspy="target: > li; cls: uk-animation-scale-up; delay: 300; repeat: true">
				<?php
				$contacts = CONTACT_HANDLER->getContacts();

				if (count($contacts) < 1) {
					echo
					"<h2 class='uk-text-center uk-animation-scale-up'>
							No contact was found
						</h2>";
				}

				foreach ($contacts as $id => $contact) {
					$name = $contact->getName();
					$number = $contact->getNumber();
					$e_mail = $contact->getEmail();

					echo
					"<li class='uk-background-muted uk-width-xlarge uk-align-center'>
							<a class='uk-accordion-title uk-padding-small'>$name</a>
							<form action='" . SELF_PATH . "' method='post'
								class='uk-accordion-content uk-padding-large uk-padding-remove-top uk-form-stacked'>
								<label for='name' class='uk-form-label'>Name</label>
								<input class='uk-input uk-form-width-large uk-margin-bottom' type='text' name='name' id='name' value='$name'>
								<label for='number' class='uk-form-label'>Phone Number</label>
								<input class='uk-input uk-form-width-large uk-margin-bottom' type='tel' name='number' id='number' value='$number'>
								<label for='e_mail' class='uk-form-label'>E-mail</label>
								<input class='uk-input uk-form-width-large uk-margin-bottom' type='email' name='e_mail' id='e_mail' value='$e_mail'>
								<input type='hidden' name='id' id='id' value='$id'>
								<div class='uk-position-relative uk-position-bottom-center'>
									<button type='submit' name='action' id='action' value='update'
										class='uk-button uk-button-text'>
										<span uk-icon='icon: push; ratio: 2'></span>
									</button>
									<button type='submit' name='action' id='action' value='delete'
										class='uk-button uk-button-text'>
										<span uk-icon='icon: trash; ratio: 2'></span>
									</button>
								</div>
							</form>
						</li>";
				}
				?>
			</ul>
		</section>
	</main>
	<footer class="uk-margin-large uk-background-muted uk-animation-slide-bottom uk-link-toggle">
		<p class="uk-text-center uk-padding-small">
			Made by <a href="https://github.com/Cromega08" target="_blank" class="uk-link">Cromega</a>
		</p>
	</footer>
</body>

</html>