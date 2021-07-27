<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="UTF-8">
	<title><?= isset($title) ? $title : SITENAME ?></title>
	<meta name="description" content="<?= isset($metadescription) ? $metadescription : '' ?>">
	<link rel="stylesheet" href="/framework/dist/style.css">
	<script src="/framework/dist/main.js"></script>
    <?php if(isset($breadcrumbs)) { ?>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
            <?php
            foreach($breadcrumbs as $key=>$item){
            echo "{
            \"@type\": \"ListItem\",
            \"position\": ".($key+1).",
            \"name\": \"$item[0]\",
            \"item\": \"$item[1]\"
            }";
            if($key+1 < count($breadcrumbs)) echo ",";
            }
            ?>
            ]
        }
        </script>
    <?php } ?>
</head>
<?php 
    use App\Models\CartSession;

    $total = 0;
    if (CartSession::existCartSession()) {
        $cart = CartSession::getCartSession();
        if($cart->books){
            foreach ($cart->books as $book) {
                $total += $book['qty'];
            }
        }
    }
?>
<body>
	<div class="page-wrapper with-navbar " id="app">
		<nav class="navbar">
			<a href="<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" ?>"><h5 class="navbar-title"><?= SITENAME ?></h5></a>
			<div class="navbar-left">	
                <div class="left-link">|</div>
                <div class="left-link"><a href="/">Accueil</a></div>
                <div class="left-link"><a href="/books">Livres</a></div>
			</div>
            <div class="navbar-right">	
                <div class="right-link"><a href="/cart"><span class="material-icons">shopping_basket</span><span class="number"><?=$total?></span></a></div>
                <?php if(isset($_SESSION['id'])) : ?>	
                    <div class="right-link">|</div>
                    <div class='right-link'><a id'' href='/lbly-admin'>Dashboard</a></div>
                    <div class="right-link"><a href="/lbly-admin/editprofil"><span class="material-icons">face</span></a></div>
                    <div class="right-link"><a href="/lbly-admin/logout"><span class="material-icons">power_settings_new</span></a></div>
                <?php endif; ?>
			</div>
		</nav>

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