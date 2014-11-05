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

/********************************
		ISSUES
*********************************/
	$html = file_get_html('http://github.com/'.$projet.'/issues');
	
	// ratio issues open/closed
	$i = 0;
	foreach ($html->find('div.table-list-header-toggle a.button-link') as $temp)
	{
		$values[$i] = intval(trim(htmlspecialchars_decode($temp->plaintext)));
		$i++;
	}
	$nb_issues_opened = intval($values[0]);
	$nb_issues_closed = intval($values[1]);
	$ratio = -1;
	if($nb_issues_closed > 0)
		$ratio = $nb_issues_opened / $nb_issues_closed;
?>
	Issues opened : <?= $nb_issues_opened ?><br />
	Issues closed : <?= $nb_issues_closed ?><br />
	ratio issues : <?= $ratio ?><br />
	</body>
</html>