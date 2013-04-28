<?php

$statusReport = MoonChecker::getReportArray();
$coreStarted = Core::isStarted();
$config_var_loaded = MoonChecker::check_Generate_Config_Vars();
$config_default_loaded = MoonChecker::check_Default_Config_File();
$config_user_loaded = MoonChecker::check_User_Config_File();
$e = MoonChecker::$lastException;

?>

<html>

	<head>
		<link 
		href='http://fonts.googleapis.com/css?family=Oxygen:400,700' rel='stylesheet' type='text/css'>
	</head>

	<body>

		<style>


		body {
			font-family: 'Oxygen', sans-serif;
			color: rgba(0, 0, 0, 0.6);
			width: 800px;
			margin: auto;
			text-align: left;
		}

		hr {
			border-color: rgba(0, 0, 0, 0.2);
			margin: 40px 0px;
			border-style: solid;
			border-width: 1px;
			border-top: none;
		}

		article:first-of-type {
			margin: 50px 0px;
			text-align: center;
		}

		article > section {
			transition: all 0.2s linear;
			-moz-transition: all 0.2s linear; /* Firefox 4 */
			-webkit-transition: all 0.2s linear; /* Safari and Chrome */
			-o-transition: all 0.2s linear; /* Opera */
			text-align: left;
			padding: 0px 5px;
			border-left: 2px solid rgba(0,0,0,0.0);
		}

		article > section:hover {
			border-top: none;
		}

		article > ul, 
		article > ol {
			text-align: left;
		}

		footer p span {color: rgba(0, 0, 0, 0.3);}

		h1, 
		h2,
		footer p {
			text-align: center;
			color: rgba(0, 0, 0, 0.4);
			font-weight: normal;
			}

		h1 > small { color: rgba(0, 0, 0, 0.3); font-size: initial; }

		body > footer {
			text-align: center;
			margin: auto;
			padding-top: 30px;
			width: 800px;
		}

		article.small > section {
			padding: 20px 0px;
			border: none;
			display: inline-block;
			vertical-align: top;
		}

		section > h3 { font-weight: normal; padding-top: 10px;}

		section > h3:before,
		section > h3:after { 
			content: ' ❖ '; 
			font-size: 1.0em; 
			color: rgba(0,0,0,0.2)
		}

		section > h3 + h4:before,
		section > h3 + h4:after {padding-right: 10px; font-size: 1.2em;}

		h3 + table tr { padding-left: 20px; display: block;}

		td + td { padding: 2px 0px 2px 20px; }

		article header h1 {	font-size: 2em;	}

		li h4, 
		li h4 + p,
		li h3, 
		li h3 + p {margin: 0px;padding: 0px;	}

		li h4 + p,
		li h3 + p  {color: rgba(0, 0, 0, 0.4);}

		article > ul li + article > ul li, 
		li + li {margin-top: 10px;}


		article > ol > li {list-style-type: asterisks;}
		article > ol[start="0"] > li:first-child {list-style-type: none;}

		.gree {color: rgba(102, 170, 63, 1);}
		.red {color: rgba(206, 99, 65, 1);}
		.small { font-size: 0.8 em; }
		.half {	width : 398px;}
		.full {	width : 798px;}
		.left,
		.left > * { text-align : left;}
		.degaged { padding: 10px;}
		.centered,
		.centered > * {	text-align: center;}

		.same-size td,
		.same-size th
		{
			width: 200px;
		}

		.gree:before { padding-right: 10px; content: '✔';}
		.red:before { padding-right: 10px; content: '✘';}


		</style>
		<article>
			<header>
				<h1>
					Allumez la cafetière...
				</h1>
			</header>
			<ol start="0">
					<li>
						<?php echo(
							'<h3 class="red">'.$e->getMessage().'</h3>'
							.'<p>Dans le fichier '
							.$e->getFile()
							.'<b> @ '
							.$e->getLine()
							.'</b></p></li>'
							);
						 ?>
					</li>
					<?php foreach ($e->getTrace() as $exc) 
					{
						echo(
							'<li><h4>'
							.$exc['class'].' '
							.$exc['type'].' '
							.$exc['function'].'('.implode(',', $exc['args']).')'
							.'</h4>'
							);

						echo(
							'<p>Dans le fichier '
							.$exc['file']
							.'<b> @ '
							.$exc['line']
							.'</b></p></li>'
							);
					}
					?>
			</ol>
		</article>
			<hr>
		<article class="small">
			<header>
				<h2>Contenu des formulaires</h2>
			</header>
			<section class="full centered">
				<h3>Post</h3>
				<table class="left degaged same-size">
					<tr>
						<th>Key</th>
						<th>Value</th>
						<th>Lenght</th>
						<th>Type</th>
					</tr>

					<?php 
					foreach ($_POST as $key => $value) {
						echo ("<tr><td>$key</td>");
						echo("<td><code>'$value'</code></td>");
						echo('<td><pre>'.strlen($value).'</pre></td>');
						echo('<td><pre>'.gettype($value).'</pre></td>');
						echo("</tr>");
					}
					?>
				</table>
			</section>
			<section class="full centered">
				<h3>Get</h3>
				<table class="left degaged same-size">
					<tr>
						<th>Key</th>
						<th>Value</th>
						<th>Lenght</th>
						<th>Type</th>
					</tr>

					<?php 
					foreach ($_GET as $key => $value) {
						echo ("<tr><td>$key</td>");
						echo("<td><code>'$value'</code></td>");
						echo('<td><pre>'.strlen($value).'</pre></td>');
						echo('<td><pre>'.gettype($value).'</pre></td>');
						echo("</tr>");
					}
					?>
				</table>
			</section>
		</article>
			<hr>
		<article class="small">
			<header>
				<h2>Analyse de l'application</h2>
			</header>
			<section class="half centered">
				<h3>Etat du Core
					<?php 
					echo($coreStarted 
						? '<h4 class="gree">Démarré</h4>' 
						: '<h4 class="red">Non démarré</h4>') 
					?>
				</h3>
			</section>
			<section class="half centered">
				<h3>Chargement des fichiers de configuration</h3>
				<?php 
					echo ($config_var_loaded === true
						? '<h4 class="gree">Les fichiers sont correctement chargés</h4>' 
						: (
							$config_default_loaded === true
							? 
								'<h4 class="red">
								Fichier de configuration de l\'application introuvable
								</h4>' 
							: 
								'<h4 class="red">
								Fichier de configuration du noyau 
								(Configuration/configuration.yml) 
								introuvable
								</h4>' 
						));

				?>
			</section>
			<section class="full centered">
				<h2>Amorçage du Core</h2>
				<table class="left degaged">
					<?php 
					foreach ($statusReport as $key => $value) {
						$res = $value === true
						? '<b class="gree">Passé</b>' 
						: (
							$value === false
							? '<b class="red">Echec</b>' 
							: '<b>Non effectué</b>'
						);
						echo ("<tr><td>$key</td><td>$res</td></tr>");
					} 
					?>
				</table>
			</section>

		</article>
		<footer>
			<p><span>Made with love with</span> <b>Moon</b> framework.</p>
		</footer>

	</body>
</html>