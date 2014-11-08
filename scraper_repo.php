<?php
	function extractNumber($s)
	{
		return 	intval(
					preg_replace('/[^0-9]/','',
						htmlspecialchars_decode(
							$s
						)
					)
				);
	}
	function scraperRepo($repo)
	{
		require_once('simple_html_dom/simple_html_dom.php');
		$now = new DateTime();

		/********************************
			HOME
		*********************************/
		$html = file_get_html('http://github.com/'.$repo.'');

		$numbers_summary = $html->find('ul.numbers-summary li a span.num');
		$nb_commits = extractNumber($numbers_summary[0]->plaintext);
		$nb_releases = extractNumber($numbers_summary[2]->plaintext);
		$nb_contributors = extractNumber($numbers_summary[3]->plaintext);

		$pagehead_actions = $html->find('ul.pagehead-actions a.social-count');
		$nb_stars = extractNumber($pagehead_actions[0]->plaintext);
		$nb_fork = extractNumber($pagehead_actions[1]->plaintext);

		for($i = 0; $i < 3 && $nb_contributors == 0; $i++) // Correction bug, 3 essais supp
		{
			$html = file_get_html('http://github.com/'.$repo.'');
			$nb_contributors = extractNumber($numbers_summary[3]->plaintext);
		}

		/********************************
			ISSUES
		*********************************/
		$html = file_get_html('http://github.com/'.$repo.'/issues');

		// ratio issues open/closed
		$i = 0;
		foreach ($html->find('div.table-list-header-toggle a.button-link') as $temp)
		{
			$values[$i] = extractNumber($temp->plaintext);
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
			$values[$i] = extractNumber($temp->plaintext);
			$i++;
		}
		$nb_PR_open = intval($values[0]);
		$nb_PR_closed = intval($values[1]);
		$ratio_PR = 0;
		if($nb_PR_closed > 0)
			$ratio_PR = $nb_PR_open / $nb_PR_closed;


		/********************************
			COMMITS
		*********************************/
		$html = file_get_html('http://github.com/'.$repo.'/commits');

		$values = $html->find('div.commits-listing div.commit-group-title');
		// Last commit
		$nb_jours_last_commit = 0;
		if(!empty($values[0]))
		{
			$value = str_replace('Commits on ', '', trim($values[0]->plaintext));
			$date_last_commit = new DateTime($value);
			$nb_jours_last_commit = $now->diff($date_last_commit)->format("%a");
		}
		// 5eme dernier jour commit
		$nb_jours_5th_day_commit = 0;
		if(!empty($values[4]))
		{
			$value = str_replace('Commits on ', '', trim($values[4]->plaintext));
			$date_5th_day_commit = new DateTime($value);
			$nb_jours_5th_day_commit = $now->diff($date_5th_day_commit)->format("%a");
		}

		// Commit depuis 3 mois
		$commit_since_3_months = 'oui';
		if($nb_jours_last_commit > 91)
			$commit_since_3_months = 'non';

		/********************************
			CONTRIBUTORS
		*********************************/
		/* NE FONCTIONNE PAS, la page est chargée en AJAX donc file_get_html ne voit aucune donnée
		$html = file_get_html('http://github.com/'.$repo.'/graphs/contributors');
		$i = 0;
		foreach ($html->find('div#contributors li.capped-card h3 span.ameta span.cmeta a.cmt') as $contributor)
		{
			$contributors[$i] = extractNumber($contributor->plaintext);
			echo $contributors[$i].'<br>';
			$i++;
		}
		*/

		/********************************
			RELEASES
		*********************************/
		$nb_jours_last_release = 0;
		if($nb_releases > 0)
		{
			$html = file_get_html('http://github.com/'.$repo.'/tags');

			$values = $html->find('td.date time');
			// Last release
			if(!empty($values[0]))
			{
				$date_last_release = new DateTime(trim($values[0]->title));
				$nb_jours_last_release = $now->diff($date_last_commit)->format("%a");
			}
		}

		/********************************
			END
		*********************************/

		return 	"$repo".
				";$nb_issues_open;$nb_issues_closed;$ratio_issues".
				";$nb_PR_open;$nb_PR_closed;$ratio_PR".
				";$nb_commits;$nb_jours_last_commit;$nb_jours_5th_day_commit;$commit_since_3_months".
				";$nb_contributors;$nb_stars;$nb_fork".
				";$nb_releases;$nb_jours_last_release";
	}
	echo scraperRepo(@$_GET['name']);
?>