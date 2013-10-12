<?php
?>
<style type="text/css">
page {font-family: freeserif; width: 100%;}
table.checkout { text-align: left; font-family: freeserif;}
table.checkout td {  font-family: courier; font-family: freeserif;}
table.checkout th {  font-family: courier; font-family: freeserif;}
</style>
<page>
<h2>XPert.kg</h2>
<h3 style="text-align: center;">Товарный чек № <?php echo time(); ?></h3>
<p style="text-align: right">Дата: <?php echo date_i18n("'F j, Y'"); ?></p>
<h4>Даваян Александр Сергеевич ИНН 21803198200829</h4>
<table class="checkout" border="1" cellspacing="0" cellpadding="4">
    <tr>
        <th style="width:108mm; ">Наименование товара</th>
        <th style="width:30mm;">Цена за еденицу</th>
        <th style="width:30mm;"nowrap="nowrap">Количество</th>
        <th style="width:30mm;">Итого</th>
    </tr>
    <?php $total = 0?>
    <?php foreach ($cart_items as $item): ?>
        <tr>
            <?php
            $custom = get_post_custom($item->ID);
            $price = $custom["price"][0];

            ?>
            <td style="width:100mm;"><?php echo $item->post_title ?></td>
            <td style="text-align: right;"><?php echo xpert_convert_price($custom["price"][0]) ?></td>
            <td style="text-align: right;"><?php echo $item->quantity; ?></td>
            <td style="text-align: right;"><?php echo xpert_convert_price($item->quantity * $price); ?></td>
        </tr>
        <?php $total += $item->quantity * $price; ?>
    <?php endforeach;?>
    <tr>
        <th></th>
        <th></th>
        <td><strong>Итого</strong></td>
        <td style="text-align: right;"><strong><?php echo xpert_convert_price($total); ?></strong></td>
    </tr>
</table>
    <p style="text-align: right;"><strong>Итого: <?php echo xpert_convert_price($total);?> сом</strong></p>
    <p style="text-align: right;"><strong>Подпись продавца: ________________</strong></p>
</page>