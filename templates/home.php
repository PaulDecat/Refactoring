<?php
/**
 * Template principal de la page d'accueil.
 * Utilise $services, $slots, $bookings et $email.
 */
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Services App</title>
	<link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
	<h1>Services App</h1>

	<section>
		<h2>Connexion</h2>
		<form method="get" action="/">
			<label>Email: <input type="email" name="mail" value="<?= e($email ?? '') ?>" required></label>
			<button type="submit">Se connecter</button>
		</form>
		<?php if(!empty($email)): ?>
			<p>Connecté: <strong><?= e($email) ?></strong></p>
		<?php endif; ?>
	</section>

	<section>
		<h2>Services</h2>
		<ul>
			<?php foreach($services as $s): ?>
				<li><?= e($s['name']) ?> (<?= e($s['id']) ?>)</li>
			<?php endforeach; ?>
		</ul>
		<h3>Ajouter un service</h3>
		<form method="post" action="/">
			<input type="hidden" name="action" value="addService">
			<input type="text" name="name" placeholder="Nom" required>
			<input type="text" name="description" placeholder="Description">
			<input type="number" name="duration" placeholder="Durée (min)">
			<button>Ajouter</button>
		</form>
	</section>

	<section>
		<h2>Créneaux</h2>
		<ul>
			<?php foreach($slots as $sl): ?>
				<li>
					<?= e($sl['id']) ?> — Service: <?= e($sl['serviceId']) ?> — Quand: <?= e($sl['datetime']) ?> — Capacité: <?= e($sl['capacity'] ?? 1) ?>
					<form method="post" action="/" style="display:inline">
						<input type="hidden" name="action" value="deleteSlot">
						<input type="hidden" name="slotId" value="<?= e($sl['id']) ?>">
						<button>Supprimer</button>
					</form>
				</li>
			<?php endforeach; ?>
		</ul>
		<h3>Ajouter un créneau</h3>
		<form method="post" action="/">
			<input type="hidden" name="action" value="addSlot">
			<select name="serviceId" required>
				<option value="">-- Service --</option>
				<?php foreach($services as $s): ?>
					<option value="<?= e($s['id']) ?>"><?= e($s['name']) ?></option>
				<?php endforeach; ?>
			</select>
			<input type="datetime-local" name="datetime" required>
			<input type="number" name="capacity" placeholder="Capacité" value="1" min="1">
			<button>Ajouter</button>
		</form>
	</section>

	<section>
		<h2>Réserver</h2>
		<form method="post" action="/">
			<input type="hidden" name="action" value="book">
			<select name="slotId" required>
				<option value="">-- Créneau --</option>
				<?php foreach($slots as $sl): ?>
					<option value="<?= e($sl['id']) ?>"><?= e($sl['id'] . ' — ' . $sl['datetime']) ?></option>
				<?php endforeach; ?>
			</select>
			<?php if(empty($email)): ?>
				<input type="email" name="userEmail" placeholder="Votre email" required>
			<?php endif; ?>
			<button>Réserver</button>
		</form>
	</section>

	<section>
		<h2>Mes réservations</h2>
		<?php if(empty($email)): ?>
			<p>Connectez-vous avec votre email pour voir vos réservations.</p>
		<?php else: ?>
			<ul>
				<?php foreach($bookings as $b): if($b['userEmail'] !== $email) continue; ?>
					<li>
						<?= e($b['id']) ?> — Slot: <?= e($b['slotId']) ?> — Créé: <?= e($b['createdAt']) ?>
						<form method="post" action="/" style="display:inline">
							<input type="hidden" name="action" value="cancel">
							<input type="hidden" name="bookingId" value="<?= e($b['id']) ?>">
							<button>Annuler</button>
						</form>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</section>

	<script src="/assets/app.js"></script>
</body>
</html>
