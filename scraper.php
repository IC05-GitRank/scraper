<html>
	<head>
		<title>Scraper for GitRank project</title>
	</head>
	<body>
<?php
	$repos = ["darul75/ng-slider","danielcrisp/angular-rangeslider","egorkhmelev/jslider","prajwalkman/angular-slider","PopSugar/angular-slider","Venturocket/angular-slider","angular-ui/ui-slider","seiyria/bootstrap-slider","CreateJS/EaselJS","mendhak/angular-intro.js","usablica/intro.js","angular/angular.js","jashkenas/backbone","emberjs/ember.js","knockout/knockout","tastejs/todomvc","spine/spine","Polymer/polymer","mozbrick/brick","facebook/react","sproutcore/sproutcore","meteor/meteor","yahoo/mojito","bitovi/canjs","derbyjs/derby","gka/chroma.js","mbostock/d3","benpickles/peity","okfn/recline","jacomyal/sigma.js","samizdatco/arbor","HumbleSoftware/envisionjs","kartograph/kartograph.js","trifacta/vega","stamen/modestmaps-js","Leaflet/Leaflet","GoodBoyDigital/pixi.js","photonstorm/phaser","melonjs/melonJS","gamelab/kiwi.js","craftyjs/Crafty","goldfire/howler.js","wellcaffeinated/PhysicsJS","piqnt/cutjs","cocos2d/cocos2d-html5","playcanvas/engine","mishoo/UglifyJS","google/closure-library","jquery/jquery","blueimp/JavaScript-MD5","jashkenas/underscore","Sage/streamlinejs","douglascrockford/JSON-js","turtl/js","blasten/turn.js","nnnick/Chart.js"];

	echo '<p id="repos_csv">
		Repo;
		Issues open;Issues closed;Ratio issues open/closed;
		PR open;PR closedRatio;PR open/closed;
		nb commits;nb jours dernier commit;nb jours 5eme dernier jour avec commit;commit sous 3 mois<br />
	';
	foreach ($repos as $repo) {
		echo '<span>'.$repo.'</span><br />
		';
	}
	echo '</p>';
?>
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
				$('#repos_csv span').each(function(key,repo) {
					var name = repo.innerText;
					$.ajax({
						url      : "scraper_repo.php?name="+name,
						data     : {'name': name},
						success  : function(data) {  
							repo.innerText = data;
						}
					}); 
				});
		});
	</script>
	</body>
</html>