<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="UTF-8">
	<title>Template de FRONT</title>
	<meta name="description" content="ceci est la description de ma page">
	<link rel="stylesheet" href="/framework/dist/style.css">
	<script src="/framework/dist/main.js"></script>
</head>

<body>
	<div class="page-wrapper with-navbar " id="app">
		<!-- mettre with-sidebar si jamais -->
		<nav class="navbar">
			<h5 class="navbar-title">Template du front</h5>
			<div class="navbar-right">
				<?= isset($_SESSION['id']) ? "<div class='right-link'><a id'' href='/lbly-admin'>Dashboard</a></div>" : ""; ?>
			</div>
		</nav>

		<div class="content-wrapper">
			<!-- intégration de la vue -->
			<?php include $this->view; ?>
		</div>

	</div>




</body>

</html>