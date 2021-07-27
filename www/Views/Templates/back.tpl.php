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
			<h3 class="sidebar-brand">
				<svg width="110" height="48" viewBox="0 0 110 48" fill="none" xmlns="http://www.w3.org/2000/svg">
					<g clip-path="url(#clip0)">
					<path d="M20.059 0C20.8847 0 21.7104 0 22.536 0C23.6959 0.385825 24.8755 0.77165 26.0354 1.15747C27.2149 0.77165 28.3945 0.385825 29.5544 0C30.3997 0 31.2647 0 32.1101 0C32.1101 0.30866 32.1101 0.636611 32.1101 0.945271H29.5544V34.6278H30.5767C32.287 34.6278 33.7025 33.8368 34.8624 32.2743C36.0026 30.7117 36.7104 28.4932 36.9463 25.6381H37.9096L37.7523 35.5152H20V34.6085H22.4967V0.945271H20.059V0Z" fill="#5897E9"/>
					<path d="M39.6586 11.4594H48.7215V34.5896H50.9233V35.5349H39.5996V34.5896H42.0963V12.4047H39.6586V11.4594ZM41.2706 3.82011C41.2706 2.81697 41.6245 1.96815 42.3519 1.25438C43.0793 0.540602 43.964 0.193359 44.9862 0.193359C46.0085 0.193359 46.8932 0.540602 47.6206 1.25438C48.348 1.96815 48.7215 2.81697 48.7215 3.82011C48.7215 4.82326 48.348 5.67207 47.6206 6.38585C46.8932 7.09962 46.0085 7.44687 44.9862 7.44687C43.964 7.44687 43.0793 7.09962 42.3716 6.38585C41.6638 5.67207 41.2706 4.82326 41.2706 3.82011Z" fill="#5897E9"/>
					<path d="M52.5769 0C53.3436 0 54.13 0 54.8967 0C55.978 0.366534 57.0789 0.713776 58.1602 1.08031C59.2611 0.713776 60.362 0.366534 61.4629 0C61.4629 4.28266 61.4629 8.56531 61.4629 12.848C62.6228 11.6519 63.9596 11.0539 65.4537 11.0539C67.9308 11.0539 69.8378 12.1728 71.1942 14.3913C72.5507 16.629 73.2192 19.5806 73.2192 23.2845C73.2192 27.2778 72.5507 30.3644 71.1942 32.5636C69.8378 34.7628 67.9701 35.8624 65.5717 35.8624C63.8417 35.8624 62.4852 35.1872 61.5219 33.8175H61.4039V35.5345H52.459V34.5892H54.8574V0.945271H52.5769V0ZM61.4826 15.105V29.6121C61.4826 31.001 61.6988 32.0813 62.151 32.8916C62.5835 33.7018 63.1733 34.0876 63.9203 34.0876C64.6477 34.0876 65.1589 33.6439 65.4341 32.7372C65.7093 31.8305 65.8469 30.3451 65.8469 28.281V17.1885C65.8469 14.1212 65.2572 12.5972 64.0579 12.5972C63.4682 12.5972 62.898 12.9058 62.3279 13.5424C61.7578 14.1791 61.4826 14.6999 61.4826 15.105Z" fill="#5897E9"/>
					<path d="M75.8535 0C76.6989 0 77.5639 0 78.4092 0C79.4708 0.501572 80.5521 1.02244 81.6137 1.52401C82.7146 1.02244 83.8155 0.501572 84.9164 0C84.9164 11.5362 84.9164 23.053 84.9164 34.5892H87.3149V35.5345H75.9125V34.5892H78.3502V0.945271H75.8535V0Z" fill="#5897E9"/>
					<path d="M88.8848 11.459H99.9136L99.8546 12.4235H97.5152L102.351 26.9691H102.469L105.359 15.8767C105.516 15.3944 105.575 14.8542 105.575 14.2755C105.575 13.0409 105.045 12.4235 103.983 12.4235H102.764V11.459H109.999L109.94 12.4235C108.957 12.4814 108.19 12.8094 107.62 13.4267C107.05 14.044 106.578 15.0857 106.205 16.5712L99.717 41.8041C99.5597 42.9616 99.0682 44.1769 98.2032 45.4502C97.3382 46.7234 95.9621 47.36 94.0748 47.36C92.738 47.36 91.6567 46.9935 90.831 46.2604C90.0053 45.5273 89.5925 44.582 89.5925 43.4053C89.5925 42.2864 89.907 41.399 90.5361 40.7431C91.1652 40.1065 92.0302 39.7785 93.1312 39.7785C93.9962 39.7785 94.7432 40.0679 95.3133 40.6659C95.9031 41.264 96.198 41.997 96.198 42.8844C96.198 43.2702 96.1194 43.6368 95.9817 44.0033L95.4706 45.0643C95.4509 45.1222 95.4313 45.1608 95.392 45.1801C95.392 45.6045 95.6475 45.8167 96.139 45.8167C96.5519 45.8167 96.9647 45.4694 97.4169 44.7942C97.869 44.0998 98.2229 43.3281 98.4588 42.4407L100.208 35.9782H99.0486L90.831 12.4428H88.8848V11.459Z" fill="#5897E9"/>
					</g>
					<path d="M0.352273 36H3.97656C6.16406 36 7.1733 34.8714 7.1733 33.3303C7.1733 31.7841 6.14915 30.8544 5.1598 30.7848V30.7053C6.0696 30.4616 6.77557 29.7557 6.77557 28.483C6.77557 26.9517 5.77628 25.8182 3.78764 25.8182H0.352273V36ZM1.28196 35.1648V31.2422H4.01634C5.38352 31.2422 6.28338 32.1669 6.28338 33.3303C6.28338 34.3842 5.54759 35.1648 3.97656 35.1648H1.28196ZM1.28196 30.4169V26.6534H3.78764C5.17969 26.6534 5.88565 27.4041 5.88565 28.483C5.88565 29.6016 5.07528 30.4169 3.87216 30.4169H1.28196ZM9.84925 38.8636C10.8038 38.8636 11.5048 38.3168 11.9125 37.2081L15.149 28.3786L14.1845 28.3636L11.9174 34.8317H11.8478L9.58079 28.3636H8.62127L11.4302 36.0746L11.1518 36.8501C10.7292 38.0036 10.1326 38.2074 9.23278 37.924L8.99414 38.7045C9.17312 38.7891 9.50124 38.8636 9.84925 38.8636Z" fill="#F2F2F2"/>
					<defs>
					<clipPath id="clip0">
					<rect width="90" height="47.36" fill="white" transform="translate(20)"/>
					</clipPath>
					</defs>
				</svg>
			</h3>
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