<?php
$tring = "bonjour tout le monde !";
echo "<p>$tring :</p>";
echo(dbQuote($tring));

$tring = ["bonjour","tout","le","monde"];
echo "<p>$tring :</p>";
echo(dbQuote($tring,"X"));
?>

<textarea>blah</textarea>
<textarea placeholder="blah"></textarea>


<h1>Classe animal</h1>
<?php echo $animal; ?>

<h1>Formulaire d'insertion</h1>
<?php echo $animal->getEditable()->generateInsertForm(); ?>

<h1>Formulaire de mise à jour</h1>
<?php echo $animal->getEditable()->generateUpdateForm(); ?>