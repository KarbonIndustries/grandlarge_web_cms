</body>
<script src="<?= JQUERY_FILE ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= JQUERY_UI_FILE ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?= JS_DIR . 'gl_cms.js' ?>" type="text/javascript" charset="utf-8"></script>

<?php
if(defined('PAGE_SCRIPT'))
{?>
<script type="text/javascript" charset="utf-8">
	<?= PAGE_SCRIPT ?>
</script>
<?}?>
</html>