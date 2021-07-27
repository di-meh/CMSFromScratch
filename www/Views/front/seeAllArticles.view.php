<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <h6 class="card-title">Tous les articles</h6>
                <div class="card-content">
                        <?php if (empty($articles)) :?>
                            <p>Il n'y a aucun article</p>
                        <?php else: ?>
                            <table class="table">
                            <thead>
                            <tr>
                                <th>titre</th>
                                <th>Voir</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($articles as $article) { ?>
                                    <tr>
                                        <td><?php echo $article["title"];?></td>
                                        <td><a href="/articles/<?=$article["slug"]?>"><button class="btn btn-primary">Voir</button></a></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</section>


