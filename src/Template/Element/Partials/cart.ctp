
<?php if ($auth['role_id'] <> 3 ) : ?>
<li class="linkdown">
  <a href="javascript:void(0);">
    <i class="fa fa-shopping-basket mr-5" style="color:#db4364;"></i>
    <span class="hidden-xs" style="color:#db4364;">
      <?= __('Pedido') ?> <sup id="count_cart" class="text-primary"><!-- CANTIDAD DE CARRITO POR JS --></sup>
      <i class="fa fa-angle-down ml-5"></i>
    </span>
  </a>
  <ul class="cart w-250">
    <li>
      <div class="cart-items">
        <ol id="items_cart" class="items">
          <!-- ITEMS DE CARRITOS DE PEDIDO POR JS -->
        </ol>
      </div>
    </li>
    <li>
      <div class="cart-footer">
        <a href="<?= $this->Url->build(['action' => 'cart']) ?>" class="pull-center"><i class="fa fa-cart-plus mr-5"></i><?= __('Ver Pedido') ?></a>
      </div>
    </li>
  </ul>
</li>
<?php endif;?>
