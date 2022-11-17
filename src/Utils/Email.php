<?php

namespace Adopet\Utils;

use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Email
{
    private array $sendTo;
    private array $replayTo;
    private array $addCc;
    private string $subject;
    private string $body;
    private string $altBody;

    public function __construct(
        array $sendTo,
        array $replayTo,
        array $addCc
    ) {
        $this->setSendTo($sendTo);
        $this->setReplayTo($replayTo);
        $this->setAddCc($addCc);
    }

    public function sendEmail($subject = "", $body = "", $altBody = ""): void
    {

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'user@example.com';                     //SMTP username
            $mail->Password   = 'secret';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('from@example.com', 'Mailer');
            if (count($this->sendTo) > 0) {
                foreach ($this->sendTo as $item) {
                    $mail->addAddress($item);
                }
            }

            if (count($this->replayTo) > 0) {
                foreach ($this->replayTo as $item) {
                    $mail->addReplyTo($item);
                }
            }
            if (count($this->addCc) > 0) {
                foreach ($this->addCc as $item) {
                    $mail->addCC($item);
                }
            }



            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altBody;
            var_dump($mail);
            exit();
            $mail->send();
            echo 'Message has been sent';
        } catch (PHPMailerException $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }


    public function setSendTo(array $sendTo): self
    {
        $this->sendTo = $sendTo;
        return $this;
    }

    public function getSendTo(): array
    {
        return $this->sendTo;
    }

    public function setReplayTo(array $replayTo): self
    {
        $this->replayTo = $replayTo;
        return $this;
    }

    public function getReplayTo(): array
    {
        return $this->replayTo;
    }

    public function setAddCc(array $addCc): self
    {
        $this->addCc = $addCc;
        return $this;
    }

    public function getAddCc(): array
    {
        return $this->addCc;
    }

    /**
     * Get the value of subject
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Set the value of subject
     *
     * @return  self
     */
    public function setSubject($subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get the value of body
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set the value of body
     *
     * @return  self
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get the value of altBody
     */
    public function getAltBody()
    {
        return $this->altBody;
    }

    /**
     * Set the value of altBody
     *
     * @return  self
     */
    public function setAltBody($altBody)
    {
        $this->altBody = $altBody;

        return $this;
    }
}
