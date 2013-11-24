<div class="cart">
    <?php $total = 0?>
    <?php foreach ($cart_items as $item): ?>
        <div class="clearfix" style="border-bottom: 1px solid #006dcc;">
            <?php
            $custom = get_post_custom($item->ID);
            $price = $custom["price"][0];
            ?>
            <div>
                <div><?php echo $item->post_title ?></div>
                <div>
                <?php echo $item->quantity; ?> шт. x <?php echo xpert_display_price($custom["price"][0])?>
                <a class="pull-right text-error" href="<?php echo get_site_url(null, '?cart_remove=' . $item->ID . '&next=' . urlencode($_SERVER["REQUEST_URI"])) ?>">X</a>
                <span style="margin-right: 10px;" class="pull-right"><?php echo xpert_display_price($custom["price"][0] * $item->quantity) ?></span>
                </div>

            </div>



        </div>
        <?php $total += $item->quantity * $price; ?>
    <?php endforeach;?>

</div>
<div class="clearfix">
    <div style="text-align: right;">
    <strong>Итого:</strong>
    <?php echo xpert_display_price($total); ?>
        </div>
</div>
<a class="btn btn-primary" href="<?php echo get_site_url(null, 'checkout') ?>">Оформить заказ</a>