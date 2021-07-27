<?php 
use App\Core\Security;
?>
<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="UTF-8">
	<title><?=SITENAME?> - Libly</title>
	<meta name="description" content="<?=SITENAME?> - Libly">
	<link rel="stylesheet" href="/framework/dist/style.css">
	<script src="/framework/dist/main.js"></script>
</head>

<body>
	<div class="page-wrapper with-navbar with-sidebar" id="app">
		<nav class="navbar">
			<a href="<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" ?>"><h5 class="navbar-title"><?= SITENAME ?></h5></a>
			<div class="navbar-right">
				<!-- Page profil mais accueil pour l'instant -->
				<div class="right-link"><a href="/lbly-admin/editprofil"><span class="material-icons">face</span></a></div>
				<div class="right-link"><a href="/lbly-admin/logout"><span class="material-icons">power_settings_new</span></a></div>
			</div>
		</nav>

		<div class="sidebar">
			<h3 class="sidebar-brand">Libly</h3>
			<ul class="sidebar-list">
				<li class="list-item"><a href="/lbly-admin"><span class="material-icons">home</span>
						<span class="item-title">Tableau de
							bord</span></a></li>
				<?php if(Security::isSuperAdmin() || Security::isAdmin()): ?>		
					<li class="list-item"><a href="/lbly-admin/orders"><span class="material-icons">receipt_long</span><span class="item-title">Commandes</span></a></li>
				<?php endif; ?>

				<?php if(Security::canCreate()): ?>
					<li class="list-item"><a href="/lbly-admin/books"><span class="material-icons">menu_book</span><span class="item-title">Livres</span></a></li>
				<?php endif; ?>
				
				<li class="list-item"><a href="/lbly-admin/category"><span class="material-icons">category</span><span class="item-title">Catégories</span></a></li>

				<?php if(Security::canCreate()): ?>
					<li class="list-item"><a href="/lbly-admin/pages"><span class="material-icons">article</span><span class="item-title">Pages</span></a></li>
				<?php endif; ?>

				<?php if(!Security::noRoles()): ?>
					<li class="list-item"><a href="/lbly-admin/articles"><span class="material-icons">article</span><span class="item-title">Articles</span></a></li>
				<?php endif; ?>

				<?php if(Security::isAdmin()): ?>
					<li class="list-item"><a href="/lbly-admin/adminview"><span class="material-icons">people</span><span class="item-title">Utilisateurs</span></a></li>
				<?php endif; ?>

				<?php if(Security::isSuperAdmin()): ?>
					<li class="list-item"><a href="/lbly-admin/settings"><span class="material-icons">settings</span><span class="item-title">Paramètres</span></a></li>
				<?php endif; ?>


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