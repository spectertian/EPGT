<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php
        $user_agent = $sf_request->getHttpHeader('user-agent');
        preg_match('|width=(\d+)|', $user_agent, $match);
        $width = isset($match[1]) ? $match[1] : '240';
        preg_match('|height=(\d+)|', $user_agent, $match);
        $height = isset($match[1]) ? $match[1] : '640';
        $scale = round($width/$height, 3);
    ?>
    <meta name="viewport" content="width=device-width; initial-scale=<?php echo $scale;?> minimum-scale=<?php echo $scale;?>; maximum-scale=<?php echo $scale;?>;"/>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <?php include_title() ?>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>

<script type="text/javascript">
var myScroll;
var a = 0;
function loaded() {
	setHeight();	// Set the wrapper height. Not strictly needed, see setHeight() function below.

	// Please note that the following is the only line needed by iScroll to work. Everything else here is to make this demo fancier.
	myScroll = new iScroll('scroller', {desktopCompatibility:true});
}

// Change wrapper height based on device orientation. Not strictly needed by iScroll, you may also use pure CSS techniques.
function setHeight() {
	var headerH = document.getElementById('toptab').offsetHeight,
		wrapperH = window.innerHeight - headerH;
	document.getElementById('wrapper').style.height = wrapperH + 'px';
}

// Check screen size on orientation change
window.addEventListener('onorientationchange' in window ? 'orientationchange' : 'resize', setHeight, false);
// Prevent the whole screen to scroll when dragging elements outside of the scroller (ie:header/footer).
// If you want to use iScroll in a portion of the screen and still be able to use the native scrolling, do *not* preventDefault on touchmove.
document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
// Load iScroll when DOM content is ready.
document.addEventListener('DOMContentLoaded', loaded, false);
</script>
    <?php include_slot("HeaderScript"); ?>
  </head>
  <body>
    <?php echo $sf_content ?>
  </body>
</html>
