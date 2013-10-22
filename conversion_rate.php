Курс валют
<?php echo "1$ = ".$rate['conversion_rate'];?>
&nbsp;
<a href="<?php echo get_site_url(null, '?change_rate=USD&next=' . urlencode($_SERVER["REQUEST_URI"])) ?>">USD</a> |
<a href="<?php echo get_site_url(null, '?change_rate=KGS&next=' . urlencode($_SERVER["REQUEST_URI"])) ?>">Сом</a>