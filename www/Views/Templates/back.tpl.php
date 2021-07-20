<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="UTF-8">
	<title>Template de BACK</title>
	<meta name="description" content="ceci est la description de ma page">
	<link rel="stylesheet" href="/framework/dist/style.css">
	<script src="/framework/dist/main.js"></script>
</head>

<body>
	<div class="page-wrapper with-navbar with-sidebar" id="app">
		<nav class="navbar">
			<h5 class="navbar-title">Template du back</h5>
			<div class="navbar-right">
				<!-- Page profil mais accueil pour l'instant -->
				<div class="right-link"><a href="/"><span class="material-icons">face</span></a></div>
				<div class="right-link"><a href="/lbly-admin/logout"><span class="material-icons">power_settings_new</span></a></div>
			</div>
		</nav>

		<div class="sidebar">
			<h3 class="sidebar-brand">Libly</h3>
			<ul class="sidebar-list">
				<li class="list-item"><a href="/lbly-admin"><span class="material-icons">home</span>
						<span class="item-title">Tableau de
							bord</span></a></li>
				<li class="list-item"><a href="/lbly-admin"><span class="material-icons">receipt_long</span><span class="item-title">Commandes</span></a></li>
				<li class="list-item"><a href="/lbly-admin/books"><span class="material-icons">menu_book</span><span class="item-title">Livres</span></a>
				</li>
				<li class="list-item"><a href="/lbly-admin/pages"><span class="material-icons">article</span><span class="item-title">Pages</span></a></li>
				<li class="list-item"><a href="/lbly-admin/articles"><span class="material-icons">article</span><span class="item-title">Articles</span></a></li>
				<li class="list-item"><a href="/lbly-admin/adminview"><span class="material-icons">people</span><span class="item-title">Utilisateurs</span></a></li>
				<li class="list-item"><a href="/lbly-admin/"><span class="material-icons">settings</span><span class="item-title">Paramètres</span></a></li>
			</ul>
		</div>
		<div class="content-wrapper">
            <?php if (isset($errors)) : ?>
                <?php foreach ($errors as $error) : ?>
                    <section class="container-fluid">
                        <div class="row">
                            <div class="col-full">
                                <div class="alert alert-danger">
                                    <button class="alert-close"><span>X</span></button>
                                    <h1 class="alert-heading">Erreur</h1>
                                    <p><?= $error; ?></p>
                                </div>
                            </div>
                        </div>
                    </section>

                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (isset($infos)) : ?>

                <?php foreach ($infos as $info) : ?>
                    <section class="container-fluid">
                        <div class="row">
                            <div class="col-full">
                                <div class="alert alert-info">
                                    <button class="alert-close"><span>X</span></button>
                                    <h1 class="alert-heading">Info</h1>
                                    <p><?= $info; ?></p>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php endforeach; ?>

            <?php endif; ?>
			<!-- intégration de la vue -->
			<?php include $this->view; ?>
		</div>

	</div>


</body>

</html>