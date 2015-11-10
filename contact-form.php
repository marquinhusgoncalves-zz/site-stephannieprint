<?php
$subjectPrefix = 'Stephannie Print';
$emailTo = '<marcus@mundosa.com.br>';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = stripslashes(trim($_POST['form-name']));
    $email   = stripslashes(trim($_POST['form-email']));
    $phone   = stripslashes(trim($_POST['form-tel']));
    $subject = stripslashes(trim($_POST['form-assunto']));
    $message = stripslashes(trim($_POST['form-mensagem']));
    $pattern = '/[\r\n]|Content-Type:|Bcc:|Cc:/i';
    if (preg_match($pattern, $name) || preg_match($pattern, $email) || preg_match($pattern, $subject)) {
        die("Header injection detected");
    }
    $emailIsValid = filter_var($email, FILTER_VALIDATE_EMAIL);
    if($name && $email && $emailIsValid && $subject && $message){
        $subject = "$subjectPrefix $subject";
        $body = "Nome: $name <br /> Email: $email <br /> Telefone: $phone <br /> Mensagem: $message";
        $headers  = "MIME-Version: 1.1" . PHP_EOL;
        $headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
        $headers .= "Content-Transfer-Encoding: 8bit" . PHP_EOL;
        $headers .= "Date: " . date('r', $_SERVER['REQUEST_TIME']) . PHP_EOL;
        $headers .= "Message-ID: <" . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>' . PHP_EOL;
        $headers .= "From: " . "=?UTF-8?B?".base64_encode($name)."?=" . "<$email>" . PHP_EOL;
        $headers .= "Return-Path: $emailTo" . PHP_EOL;
        $headers .= "Reply-To: $email" . PHP_EOL;
        $headers .= "X-Mailer: PHP/". phpversion() . PHP_EOL;
        $headers .= "X-Originating-IP: " . $_SERVER['SERVER_ADDR'] . PHP_EOL;
        mail($emailTo, "=?utf-8?B?".base64_encode($subject)."?=", $body, $headers);
        $emailSent = true;
    } else {
        $hasError = true;
    }
}
?>
<div><?php include 'header.php';?></div>
    <?php if(!empty($emailSent)): ?>
        <div class="col-md-6 col-md-offset-3">
            <div class="alert alert-success text-center">Sua mensagem foi enviada com sucesso.</div>
        </div>
    <?php else: ?>
        <?php if(!empty($hasError)): ?>
        <div class="col-md-5 col-md-offset-4">
            <div class="alert alert-danger text-center">Houve um erro no envio, tente novamente mais tarde.</div>
        </div>
        <?php endif; ?>

    <div class="col-md-6 col-md-offset-3">
        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="contact-form" class="form-horizontal" role="form" method="post">
            <div class="form-group">
                <label for="name" class="col-lg-2 control-label">Nome</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="form-name" name="form-name" placeholder="Nome" required>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-lg-2 control-label">Email</label>
                <div class="col-lg-10">
                    <input type="email" class="form-control" id="form-email" name="form-email" placeholder="Email" required>
                </div>
            </div>
            <div class="form-group">
                <label for="tel" class="col-lg-2 control-label">Telefone</label>
                <div class="col-lg-10">
                    <input type="tel" class="form-control" id="form-tel" name="form-tel" placeholder="Telefone">
                </div>
            </div>
            <div class="form-group">
                <label for="assunto" class="col-lg-2 control-label">Assunto</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="form-assunto" name="form-assunto" placeholder="Assunto" required>
                </div>
            </div>
            <div class="form-group">
                <label for="mensagem" class="col-lg-2 control-label">Mensagem</label>
                <div class="col-lg-10">
                    <textarea class="form-control" rows="3" id="form-mensagem" name="form-mensagem" placeholder="Mensagem" required></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <button type="submit" class="btn btn-default">Enviar</button>
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>

</body>
</html>