<?php

echo "<table border='1'>";
	echo "<tr>";
	if (!empty($invoices)) {
		$keys = array_keys($invoices[0]->attributes);
		foreach ($keys as $key) {
			echo "<th>" . htmlspecialchars($key) . "</th>";
		}
	}
	echo "</tr>";

foreach ($invoices as $invoice) {
	echo "<tr>";
	foreach ($invoice->attributes as $value) {
		echo "<td>" . htmlspecialchars($value) . "</td>";
	}
	echo "</tr>";
}
echo "</table>";

