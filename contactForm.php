<?php
$sender = '';
$message = '';
$antispam = '';
if ($_POST) {
    define('WEBMASTER_MAIL', 'zetvuegm0@gmail.com');
    define('EMAIL_SUBJECT', 'Somebody sent you a message through the contact form.');
    define('ANTISPAM_SOLUTION', 'green'); //Will be treated as case-insensitive

    if (
        isset($_POST['sender']) && $_POST['sender'] &&
        isset($_POST['message']) && $_POST['message'] &&
        isset($_POST['antispam']) && $_POST['antispam']
    ) {
        $sender = filter_var($_POST['sender'], FILTER_VALIDATE_EMAIL);
        $message = htmlspecialchars($_POST['message'], ENT_QUOTES);
        $antispam = strtolower(trim($_POST['antispam']));

        if ($antispam === ANTISPAM_SOLUTION) {
            $headers[] = 'From: '.$sender;
            $headers[] = 'Reply-To: '.$sender;
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=utf-8';
            $emailBody = '
            <p>This message was sent on '.date('w, m/d/Y H:i:s'). '</p>
            <div style="border: 1px solid black; padding: 8px; color: #030">' .$message.'</div>
            <p>Reply to this e-mail directly to contact the sender back.</p>
            ';

            $result = mail(WEBMASTER_MAIL, EMAIL_SUBJECT, $emailBody, implode("\n", $headers));

            if ($result) {
                $contactFormResponse = 'The message has successfully been sent. Expect an answer in your mailbox soon.';
                $sender = '';
                $message = '';
                $antispam = '';
            }
            else {
                $contactFormResponse = '
            The message couldn\'t be sent because of any unknown error.
            Please, copy the message to your standard mail client and sent it to <a href="mailto:'.WEBMASTER_MAIL.'">'.WEBMASTER_MAIL.'</a>.
            We apologise for the inconvenience.';
            }
        }
        else {
            $contactFormResponse = 'The answer to the antispam protection is not correct ('.strlen(ANTISPAM_SOLUTION).'-letter answer excepted).';
        }
    }
    else {
        $contactFormResponse = 'Fill in all the fields please.';
    }
}

