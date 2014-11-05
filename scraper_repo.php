<?php
	function scraper_repo($repo)
	{
		require_once('simple_html_dom/simple_html_dom.php');
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

		return "$repo;$nb_issues_open;$nb_issues_closed;$ratio_issues;$nb_PR_open;$nb_PR_closed;$ratio_PR";
	}
	echo scraper_repo(@$_GET['name']);
?>