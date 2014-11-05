<?php

$projet = "gephi/gephi";
require_once('simple_html_dom/simple_html_dom.php');


?>
<html>
	<head>
		<title>Scraper for GitRank project</title>
	</head>
	<body>
<?php
	function scraper_repo($repo)
	{
		/********************************
			ISSUES
		*********************************/
		$html = file_get_html('http://github.com/'.$repo.'/issues');

		// ratio issues open/closed
		$i = 0;
		foreach ($html->find('div.table-list-header-toggle a.button-link') as $temp)
		{
			$values[$i] = intval(trim(htmlspecialchars_decode($temp->plaintext)));
			$i++;
		}
		$nb_issues_open = intval($values[0]);
		$nb_issues_closed = intval($values[1]);
		$ratio_issues = 0;
		if($nb_issues_closed > 0)
			$ratio_issues = $nb_issues_open / $nb_issues_closed;

		/********************************
			PULL_REQUEST
		*********************************/
		$html = file_get_html('http://github.com/'.$repo.'/pulls');

		// ratio PR open/closed
		$i = 0;
		foreach ($html->find('div.table-list-header-toggle a.button-link') as $temp)
		{
			$values[$i] = intval(trim(htmlspecialchars_decode($temp->plaintext)));
			$i++;
		}
		$nb_PR_open = intval($values[0]);
		$nb_PR_closed = intval($values[1]);
		$ratio_PR = 0;
		if($nb_PR_closed > 0)
			$ratio_PR = $nb_PR_open / $nb_PR_closed;

		return "$ratio_issues;$ratio_PR";
	}
?>
Repo;Issues open;Issues closed;Ratio issues open/closed;PR open;PR closedRatio PR open/closed
<br/>
<?php
	$repos = ["darul75/ng-slider","danielcrisp/angular-rangeslider","egorkhmelev/jslider","prajwalkman/angular-slider","PopSugar/angular-slider","Venturocket/angular-slider","angular-ui/ui-slider","seiyria/bootstrap-slider","CreateJS/EaselJS","mendhak/angular-intro.js","usablica/intro.js","angular/angular.js","jashkenas/backbone","emberjs/ember.js","knockout/knockout","tastejs/todomvc","spine/spine","Polymer/polymer","mozbrick/brick","facebook/react","sproutcore/sproutcore","meteor/meteor","yahoo/mojito","bitovi/canjs","derbyjs/derby","gka/chroma.js","mbostock/d3","benpickles/peity","okfn/recline","jacomyal/sigma.js","samizdatco/arbor","HumbleSoftware/envisionjs","kartograph/kartograph.js","trifacta/vega","stamen/modestmaps-js","Leaflet/Leaflet","GoodBoyDigital/pixi.js","photonstorm/phaser","melonjs/melonJS","gamelab/kiwi.js","craftyjs/Crafty","goldfire/howler.js","wellcaffeinated/PhysicsJS","piqnt/cutjs","cocos2d/cocos2d-html5","playcanvas/engine","mishoo/UglifyJS","google/closure-library","jquery/jquery","blueimp/JavaScript-MD5","jashkenas/underscore","Sage/streamlinejs","douglascrockford/JSON-js","turtl/js","blasten/turn.js","nnnick/Chart.js"];
	foreach ($repos as $repo) {
		echo scraper_repo($repo) . '<br />';
	}
?>
	</body>
</html>