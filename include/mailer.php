<?php 
/**
 * Mailer.php
 *
 * The Mailer class is meant to simplify the task of sending
 * emails to users. Note: this email system will not work
 * if your server is not setup to send mail.
 *
 * If you are running Windows and want a mail server, check
 * out this website to see a list of freeware programs:
 * <http://www.snapfiles.com/freeware/server/fwmailserver.html>
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 19, 2004
 */
 
class Mailer
{
   /**
    * sendWelcome - Sends a welcome message to the newly
    * registered user, also supplying the username and
    * password.
    */
   function sendWelcome($user, $email, $pass, $userid){
      $from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
      $subject = "Jpmaster77's Site - Welcome!";
      $body = $user.",\n\n"
             ."Welcome! You've just registered at Jpmaster77's Site "
             ."with the following information:\n\n"
             ."Username: ".$user."\n"
             ."Password: ".$pass."\n\n"
             ."Before you can login you need to activate your\n"
             ."account by clicking on this link:\n\n"
             ."http://localhost:8888/ls2/valid.php?qs1=".$user."&qs2=".$userid."\n\n"
             ."If you ever lose or forget your password, a new "
             ."password will be generated for you and sent to this "
             ."email address, if you would like to change your "
             ."email address you can do so by going to the "
             ."My Account page after signing in.\n\n"
             ."- Jpmaster77's Site";

      return mail($email,$subject,$body,$from);
   }
   
   /**
    * sendConfirmation - Sends a confirmation to users
    * who click a "Send confirmation" button.  This
    * only needs to be used if the EMAIL_WELCOME constant
    * is changed to true and the user's 'valid' field is 0
    */
   function sendConfirmation($user, $userid, $email){
       $from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
       $subject = "jpmaster77's Site - Welcome!";
       $body = $user.",\n\n"
               ."We're sorry for the inconvenience.  We are making\n"
               ."our website more secure for both your and our \n"
               ."benefit.\n\n"
               ."To activate your account you can either click on the\n"
               ."following link or copy the link and paste it into your\n"
               ."address bar.\n\n"
               ."http://localhost:8888/ls2/valid.php?qs1=".$user."&qs2=".$userid."\n\n"
               ."We here at Jpmaster77's Site hope you continue to\n"
               ."enjoy our wonderful service.\n\n"
               ."Sincerely,\n\n"
               ."- Jpmaster77's Site";
               
      return mail($email,$subject,$body,$from);
   }
   
   
   /**
    * sendNewPass - Sends the newly generated password
    * to the user's email address that was specified at
    * sign-up.
    */
   function sendNewPass($user, $email, $pass){
      $from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
      $subject = "Jpmaster77's Site - Your new password";
      $body = $user.",\n\n"
             ."We've generated a new password for you at your "
             ."request, you can use this new password with your "
             ."username to log in to Jpmaster77's Site.\n\n"
             ."Username: ".$user."\n"
             ."New Password: ".$pass."\n\n"
             ."It is recommended that you change your password "
             ."to something that is easier to remember, which "
             ."can be done by going to the My Account page "
             ."after signing in.\n\n"
             ."- Jpmaster77's Site";
             
      return mail($email,$subject,$body,$from);
   }
};

/* Initialize mailer object */
$mailer = new Mailer;
 
?>
