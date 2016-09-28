<?
	include_once 'functions.php';

	if($_REQUEST['add']) {
		$hex = explodeHex($_REQUEST['hex']);
		$newColor = addToPalette($hex);
	} elseif($_REQUEST['palette']) {
		$newColor = loadPalette($_REQUEST['palette']);
	}	elseif($_REQUEST['remove']) {
		removeFromPalette($_REQUEST['remove']);
	}

	$palette = $_SESSION['palette'];
?>
<? if(count($palette)): ?>
<a href="#picker" class="skip">Skip to the selected color</a>
<nav class="palette">
	<ul>
	<? foreach($palette as $swatch): ?>
		<? $temp = str_replace('#','',$swatch['hex']); ?>
		<li>
			<a href="?show=<?= $temp ?>" class="show" style="background: <?= $swatch['hex'] ?>" title="Color Estimate: A <?= $swatch['name'] ?>"></a>
			<a href="?remove=<?= $temp ?>" class="warn remove" title="Remove <?= $swatch['name'] ?> from your palette"></a>
		</li>
		<? $share.= ','.$temp; ?>
	<? endforeach; ?>
	</ul>
	<div class="controls">
		<? $share = substr($share,1); ?>
		<a href="?palette=<?= $share ?>" title="Share this palette" accesskey="S"><span>Share</span></a>
		<a href="?clear=1" class="warn" title="Clear the whole palette" accesskey="X"><span>Clear</span></a>
	</div>
</nav>
<? endif; ?>