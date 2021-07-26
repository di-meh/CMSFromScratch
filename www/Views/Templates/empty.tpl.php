<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="UTF-8">
	<title>Libly</title>
	<meta name="description" content="ceci est la description de ma page">
	<link rel="stylesheet" href="/framework/dist/style.css">
	<script src="/framework/dist/main.js"></script>
</head>

<body>
	<div class="page-wrapper " id="app">
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