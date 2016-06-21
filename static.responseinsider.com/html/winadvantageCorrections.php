<html>
	<body>
		<h1>Insert your corrections here</h1><br/>
		<form method="post" action='http://192.41.48.83/php/winadvantage.php'>
			<input type="hidden" name="Corrected" value="1" />
			<table>
				<?php
					foreach($_REQUEST as $key => $val){
						echo "<tr>";
						echo "<td> $key</td>";
						echo '<td><input type="input" name="'. $key . '" value="' . $val . '" /></td>';
						echo "</tr>";
					}
				?>
				<tr>
					<td><input type="submit" value="Send to Win Advantage" /></td><td></td>
				</tr>
			</table>
		</form>
	</body>
</html>

