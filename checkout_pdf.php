<?php
?>
<style type="text/css">
page {font-family: freeserif; width: 100%;}
table.checkout { text-align: left; font-family: freeserif;}
table.checkout td {  font-family: courier; font-family: freeserif;}
table.checkout th {  font-family: courier; font-family: freeserif;}
</style>
<page>
<table border="0">
    <tr>
        <td style="width: 100mm;">
            <img src="<?php echo get_site_url() ?>/wp-content/themes/the-bootstrap/img/logo_xpert.png">
        </td>
        <td style="width: 96mm; text-align: right;">
            <?php echo date_i18n("d.m.Y"); ?><br />
            ИП ДАВАЯН АЛЕКСАНДР СЕРГЕЕВИЧ<br />
            ИНН 21803198700829<br />
            р/c1171020039009780<br />
            Бик 117010<<br />
            ФЗАО "Исламик" "ЭкоИсламикБанк"
        </td>
    </tr>

</table>
<hr />
<h3 style="text-align: center;">Товарный чек № <?php echo time(); ?></h3>

<table class="checkout" border="1" cellspacing="0" cellpadding="4">
    <tr>
        <th style="width:6mm; text-align: center;">№</th>
        <th style="width:100mm; ">Наименование товара</th>
        <th style="width:25mm;">Цена</th>
        <th style="width:30mm;"nowrap="nowrap">Кол-во ед.</th>
        <th style="width:30mm;">Итого</th>
    </tr>
    <?php $total = 0?>
    <?php $number = 1;?>
    <?php foreach ($cart_items as $item): ?>
        <tr>
            <?php
            $custom = get_post_custom($item->ID);
            $price = $custom["price"][0];

            ?>
            <td style="text-align: center"><?php echo $number; ?></td>
            <td style="width:100mm;"><?php echo $item->post_title ?></td>
            <td style="text-align: right;"><?php echo xpert_convert_price($custom["price"][0]) ?></td>
            <td style="text-align: right;"><?php echo $item->quantity; ?></td>
            <td style="text-align: right;"><?php echo xpert_convert_price($item->quantity * $price); ?></td>
        </tr>
        <?php $total += $item->quantity * $price; $number++;?>
    <?php endforeach;?>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <td><strong>Итого</strong></td>
        <td style="text-align: right;"><strong><?php echo xpert_convert_price($total); ?></strong></td>
    </tr>

</table>
    <p><u>Всего наименований <?php echo count($cart_items); ?>, на сумму: <?php echo xpert_convert_price($total);?> сом</u></p>
    <table border="0" width="100%">
        <tr>
            <th style="text-align: left; vertical-align: bottom;"><strong>Подпись продавца:</strong> </th>
            <td style="border-bottom: 1px solid black;"><img  width="100"src="<?php echo get_site_url()?>/wp-content/uploads/davayan_signature.png"></td>
        </tr>

    </table>

</page>