<?php
$page_title = 'Pago · Tienda Virtual';
include 'template/header.php';
?>

<h1 class="h3">Pago Seguro</h1>
<div class="row g-3 mt-2">
  <div class="col-lg-7">
    <div class="card shadow-sm">
      <div class="card-body">
        <h2 class="h5">Método de pago</h2>
        <div class="form-check"><input class="form-check-input" type="radio" name="payMethod" id="payCard" value="card"
            checked><label class="form-check-label" for="payCard">Tarjeta de crédito / débito</label></div>
        <div class="form-check"><input class="form-check-input" type="radio" name="payMethod" id="payPayPal"
            value="paypal"><label class="form-check-label" for="payPayPal">PayPal</label></div>
        <hr />
        <form id="cardForm" novalidate>
          <div class="row g-3">
            <div class="col-md-12"><label class="form-label">Dirección de Envío</label><textarea class="form-control"
                id="direccion_envio" name="direccion_envio" rows="3" required></textarea>
              <div class="invalid-feedback">Por favor ingrese su dirección.</div>
            </div>
            <div class="col-md-6"><label class="form-label">Nombre en la tarjeta</label><input class="form-control"
                id="cardName" required maxlength="80" />
              <div class="invalid-feedback">Ingresa el nombre.</div>
            </div>
            <div class="col-md-6"><label class="form-label">Número de tarjeta</label><input class="form-control"
                id="cardNumber" inputmode="numeric" maxlength="19" placeholder="#### #### #### ####" required />
              <div class="invalid-feedback">Número inválido.</div>
            </div>
            <div class="col-md-6"><label class="form-label">Vence (MM/AAAA)</label><input class="form-control"
                id="cardExp" placeholder="MM/AAAA" required />
              <div class="invalid-feedback">Fecha inválida.</div>
            </div>
            <div class="col-md-6"><label class="form-label">CVV</label><input class="form-control" id="cardCVV"
                inputmode="numeric" maxlength="4" placeholder="***" required />
              <div class="invalid-feedback">CVV inválido.</div>
            </div>
          </div>

          <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" value="true" id="terms" name="terms" required>
            <label class="form-check-label" for="terms">Acepto los términos y condiciones</label>
            <div class="invalid-feedback">Debes aceptar los términos y condiciones.</div>
          </div>
          
          <button class="btn btn-primary w-100 mt-3" id="payBtn" type="submit"><i class="fa fa-credit-card me-1"></i>
            Pagar ahora</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h2 class="h6">Resumen</h2>
        <div class="d-flex justify-content-between"><span>Productos</span><strong id="summaryItems">0</strong></div>
        <div class="d-flex justify-content-between"><span>Subtotal</span><strong id="summarySubtotal">₡0</strong></div>
        <div class="d-flex justify-content-between"><span>Impuesto (IVA)</span><strong id="summaryTax">₡0</strong></div>
        <div class="d-flex justify-content-between"><span>Envío</span><strong id="summaryShipping">₡0</strong></div>
        <hr />
        <div class="d-flex justify-content-between fs-5"><span>Total</span><strong id="summaryTotal">₡0</strong></div>
      </div>
    </div>
  </div>
</div>

<?php
include 'template/footer.php';
echo '<script type="module" src="assets/js/payment.js"></script>';
?>