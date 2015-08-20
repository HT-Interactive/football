<?php
// multiple recipients
$to  = 'jeffrey.moreland@gmail.com' . ', '; // note the comma
$to .= 'jeff@evose.com';

// subject
$subject = 'PHP HTML Test';

// message
$message = '
<html>
<head>
  <title>Test</title>
</head>
<body>
  <p>Test Table</p>
  <table>
    <tr>
      <th>Person</th><th>Day</th><th>Month</th><th>Year</th>
    </tr>
    <tr>
      <td>Joe</td><td>3rd</td><td>August</td><td>1970</td>
    </tr>
    <tr>
      <td>Sally</td><td>17th</td><td>August</td><td>1973</td>
    </tr>
  </table>
</body>
</html>
';

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
//$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
//$headers .= 'From: Birthday Reminder <birthday@example.com>' . "\r\n";
//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";

// Mail it
if(mail($to, $subject, $message, $headers)) {
	echo 'Mail sent.';
} else {
	echo 'Mail Error';
}
?>