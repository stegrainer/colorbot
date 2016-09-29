<?
	include_once 'functions.php';
	
	if($_REQUEST['convert']) {
		$newColor = convertColor($_REQUEST);
	} elseif($_REQUEST['show']) {
		$newColor = explodeHex($_GET['show']);
	} elseif($_REQUEST['palette']) {
		$shareLink = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	} elseif(!$newColor) {
		$newColor = generateRandomColor();
	}
	
	$dark = gradeColor($newColor, -35);
	$darkColor = outputAllModes($dark);
	$color = outputAllModes($newColor);

	if($shareLink) {
		$yay = array('Nice palette!',
		             'WHOA! Sweet colors!',
		             'These hues please me.',
		             'Marvelous mix!');
		$par = array('You can share it with this link:',
		             'Share them with this link:',
		             'You must share them with others:',
		             'Share it with the world:');
	} else {
		$pre = array('Hmmm, looks like a ',
		             'It&rsquo;s a bird! It&rsquo;s a plane! It&rsquo;s a ',
		             'Roy G. Biv! I do believe that&rsquo;s a ',
		             'Computing best guess. Found match: ');
		$post = array(' through my Hue-Matic 2016 sensor array.',
		              ' background.',
		              '. What do y&rsquo;all see?',
		              '. User perception may vary.');
	}
	if($_SESSION['visited'] > 3) {
		$_SESSION['visited']++;
		$greeting = array('Welcome back!',
		                  'I&rsquo;m happy to see you!',
		                  'You look nice today!',
		                  'How can I help you?');
		$intro = false;
	} else {
		$_SESSION['visited']++;
		$greeting = "Hello, I&rsquo;m ColorBot!";
		$intro = true;
	}
	$pickOne = rand(0,3);
?>

<style>
	label { color: <?= $darkColor['hex'] ?>; }
	input[type="text"] { text-shadow: 1px 1px 0 <?= $darkColor['hex'] ?>; }
	input[type="text"]:focus, input[type="text"].changed { border-color: <?= $darkColor['hex'] ?>; }
</style>
<div class="picker <?= getContrastYIQ(str_replace('#','',$color['hex'])) ?> " style="background-color: <?= $color['hex'] ?>">
	<section class="primary">
		<div class="content">
			<div id="logo"></div>
		<? if($shareLink): ?>
			<h1><?= $yay[$pickOne] ?></h1>
			<p>
				<?= $par[$pickOne] ?><br />
				<a href="<?= $shareLink ?>" id="share"><?= $shareLink ?></a>
			</p>
		<? else: ?>
			<? if($intro): ?>
			<h1>Hello, I&rsquo;m ColorBot!</h1>
			<p>
				I was created to be your personal color assistant. I can convert colors
				between Hexadecimal, RGBa, or HSLa. I can help you create and share a
				color palette. I can even attempt to name the color you&rsquo;ve selected,
				though my sensors are somewhat rudimentary.
			</p>
			<? else: ?>
			<h1><?= $greeting[$pickOne] ?></h1>
			<? endif; ?>
			<p><?= $pre[$pickOne] ?><strong><?= $color['name'] ?></strong><?= $post[$pickOne] ?></p>
		<? endif; ?>
			<form action="./" method="post">
				<input type="hidden" name="hex-o" value="<?= $color['hex'] ?>">
				<input type="hidden" name="rgb-o" value="<?= $color['rgb'] ?>">
				<input type="hidden" name="hsl-o" value="<?= $color['hsl'] ?>">
				
				<div class="field">
					<label for="hex" accesskey="H">Hexadecimal</label>
					<input type="text" name="hex" id="hex" value="<?= $color['hex'] ?>" maxlength="7">
				</div>
				<div class="field">
					<label for="rgb" accesskey="G">RGBa</label>
					<input type="text" name="rgb" id="rgb" value="<?= $color['rgb'] ?>">
				</div>
				<div class="field">
					<label for="hsl" accesskey="L">HSLa</label>
					<input type="text" name="hsl" id="hsl" value="<?= $color['hsl'] ?>">
				</div>
				<div class="actions">
					<button type="submit" name="convert" value="1" accesskey="C" aria-label="Convert the selected color">convertColor()</button>
					<button type="submit" name="add" value="1" accesskey="A" aria-label="Add this color to your palette">addToPalette()</button>
					<button type="submit" name="randomize" value="1" accesskey="R" aria-label="Generate a new random color">randomColor()</button>
				</div>
			</form>
			<footer>
				<a href="https://stegrainer.com">Made with care by Ste Grainer</a>
				<a href="https://stegrainer.com/contact/">Submit feedback</a>
			</footer>
		</div>
	</section>
	<section class="grades">
		<ul>
		<? for($i = 22.5; $i>=-22.5; $i-=15): ?>
			<?
				$grade = gradeColor($newColor, $i);
				$gradeModes = outputAllModes($grade);
			?>
			<li style="background: <?= $gradeModes['hex'] ?>">
				<a href="?show=<?= str_replace('#','',$gradeModes['hex']) ?>" title="Color estimate: A <?= $gradeModes['name'] ?>">
					<span><?= $gradeModes['hex'] ?></span>
				</a>
			</li>
		<? endfor; ?>
		</ul>
	</section>
</div>