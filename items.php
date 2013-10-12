<?php
?>
<table class="cart">
    <tr>
        <th>Наименование</th>
        <th>Цена</th>
        <th nowrap="nowrap">Кол-во</th>
    </tr>
    <?php $total = 0?>
    <?php foreach ($cart_items as $item): ?>
        <tr>
            <?php
            $custom = get_post_custom($item->ID);
            $price = $custom["price"][0];
            ?>
            <td><?php echo $item->post_title ?></td>

            <td><?php echo xpert_display_price($custom["price"][0]) ?></td>
            <td><?php echo $item->quantity; ?>
                <a class="pull-right text-error"
                   href="<?php echo get_site_url(null, '?cart_remove=' . $item->ID . '&next=' . urlencode($_SERVER["REQUEST_URI"])) ?>">X</a>
            </td>
        </tr>
        <?php $total += $item->quantity * $price; ?>
    <?php endforeach;?>
    <tr>
        <th></th>
        <td><strong>Итого</strong></td>
        <td nowrap="nowrap"><strong><?php echo xpert_display_price($total); ?></strong></td>
    </tr>
</table>

<a class="btn btn-primary" href="<?php echo get_site_url(null, 'checkout') ?>">Оформить заказ</a>

<!--<div class="pull-right">-->
<!---->
<!--<a class="btn btn-danger btn-mini" href="--><?php //echo get_site_url(null, '?clear_cart=1&next='.urlencode($_SERVER["REQUEST_URI"])) ?><!--">Очистить коризину</a>-->
<!--    </div>-->
