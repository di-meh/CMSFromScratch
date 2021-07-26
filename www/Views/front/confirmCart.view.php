<script src="https://js.stripe.com/v3/"></script>
<section class="container-fluid">
    <div class="row">
        <div class="col-full">
            <div class="card">
                <h6 class="card-title">Paiement</h6>
                <div class="card-content">
                    <form id="payment-form" method="post" action="/payment-success">
                        <div id="errors"></div>
                        <div class="input-group">
                            <label for="cardholder-name">Titulaire de la carte :</label>
                            <input id="cardholder-name" name="cardholder-name" type="text" placeholder="PRENOM NOM" required>
                        </div>
                        <div class="input-group">
                            <label for="email">Adresse de messagerie :</label>
                            <input id="email" name="email" type="text" placeholder="example@email.com" required>
                        </div>
                        <div class="input-group">
                            <label for="card-element">Informations de carte :</label>
                            <div class="input-control" id="card-element" name="card-element"></div>
                        </div>
                        <div class="input-group" id="card-errors" role="alert"></div>
                        <button id="card-button" class="btn btn-primary" type="button" data-secret="<?= $intent['client_secret'] ?>">Valider le paiement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>