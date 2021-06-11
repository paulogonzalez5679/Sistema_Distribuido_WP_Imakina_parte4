<?php
stm_lms_register_style('reviews');
?>

<div class="container">

    <form id="contact-form" name="contact-form" action="<?php echo get_permalink();?>#contact-form" method="post">
        <?php //Comprobamos si el formulario ha sido enviado
        if (isset( $_POST['btn-submit'] )) {
        //Creamos una variable para almacenar los errores
        global $reg_errors;
        $reg_errors = new WP_Error;
            
        //Recogemos en variables los datos enviados en el formulario y los sanitizamos.
        //Si detectamos algún error, podremos más abajo rellenar los campos del formulario con los datos enviados para no tener que empezar el formulario de cero
        $f_name = sanitize_text_field($_POST['f_name']);
        $f_email = sanitize_email($_POST['f_email']);
        $f_phone = sanitize_text_field($_POST['f_phone']);
        $f_message = sanitize_text_field($_POST['f_message']);
        $f_course = get_the_title();

        //El campo Nombre es obligatorio, comprobamos que no esté vacío y en caso contrario creamos un registro de error
        if ( empty( $f_name ) ) {
            $reg_errors->add("empty-name", "El campo nombre es obligatorio");
        }
        //El campo Email es obligatorio, comprobamos que no esté vacío y en caso contrario creamos un registro de error
        if ( empty( $f_email ) ) {
            $reg_errors->add("empty-email", "El campo e-mail es obligatorio");
        }
        //Comprobamos que el dato tenga formato de e-mail con la función de WordPress "is_email" y en caso contrario creamos un registro de error
        if ( !is_email( $f_email ) ) {
            $reg_errors->add( "invalid-email", "El e-mail no tiene un formato válido" );
        }
        //El campo Mensaje es obligatorio, comprobamos que no esté vacío y en caso contrario creamos un registro de error
        if ( empty( $f_message ) ) {
            $reg_errors->add("empty-message", "El campo consulta es obligatorio");
        }

        //Si no hay errores enviamos el formulario
        if (count($reg_errors->get_error_messages()) == 0) {
            //Destinatario
            $recipient = "r.lopez@mycodedmind.com";

            //Asunto del email
            $subject = 'Formulario de contacto curso' . get_the_title();

            //La dirección de envio del email es la de nuestro blog por lo que agregando este header podremos responder al remitente original
            $headers = "Reply-to: " . $f_name . " <" . $f_email . ">\r\n";

            //Montamos el cuerpo de nuestro e-mail
            $message = "Curso: " . $f_course . "<br>";
            $message = "Nombre: " . $f_name . "<br>";
            $message .= "E-mail: " . $f_email . "<br>";
            $message .= "Teléfono: " . $f_phone . "<br>";
            $message .= "Mensaje: " . nl2br($f_message) . "<br>";
                            
            //Filtro para indicar que email debe ser enviado en modo HTML
            add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));
                            
            //Por último enviamos el email
            $envio = wp_mail( $recipient, $subject, $message, $headers, $attachments);

            //Si el e-mail se envía correctamente mostramos un mensaje y vaciamos las variables con los datos. En caso contrario mostramos un mensaje de error
            if ($envio) {
            unset($f_name);
            unset($f_email);
            unset($f_phone);
            unset($f_message);?>
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                El formulario ha sido enviado correctamente.
            </div>
            <?php }else {?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Se ha producido un error enviando el formulario. Puede intentarlo más tarde o ponerse en contacto con nosotros escribiendo un mail a "destinatario@email.com"
            </div>
            <?php }
        }
        }?>

        <div class="form-group">
        <label for="f_name">Nombre <span class="asterisk">*</span></label>
        <input type="text" id="f_name" name="f_name" class="form-control" value="<?php echo $f_name;?>" placeholder="Introduce tu nombre" required aria-required="true">

        <?php //Comprobamos si hay errores en la validación del campo Nombre
        if ( is_wp_error( $reg_errors ) ) {
            if ($reg_errors->get_error_message("empty-name")) {?>
            <br class="clearfix" />
            <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p><?php echo $reg_errors->get_error_message("empty-name");?></p>
            </div>
            <?php }
        }?>
        </div>

        <div class="form-group">
        <label for="f_email">E-mail <span class="asterisk">*</span></label>
        <input type="email" id="f_email" name="f_email" class="form-control" value="<?php echo $f_email;?>" placeholder="Introduce tu e-mail" required aria-required="true">

        <?php //Comprobamos si hay errores en la validación del campo E-mail
        if ( is_wp_error( $reg_errors ) ) {
            if ($reg_errors->get_error_message("empty-email")) {?>
            <br class="clearfix" />
            <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p><?php echo $reg_errors->get_error_message("empty-email");?></p>
            </div>
            <?php }
        }

        //Comprobamos si hay errores en el formato del campo E-mail
        if ( is_wp_error( $reg_errors ) ) {
            if ($reg_errors->get_error_message("invalid-email")) {?>
            <br class="clearfix" />
            <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p><?php echo $reg_errors->get_error_message("invalid-email");?></p>
            </div>
            <?php }
        }?>
        </div>

        <div class="form-group">
        <label for="f_phone">Teléfono</label>
        <input type="tel" id="f_phone" name="f_phone" class="form-control" value="<?php echo $f_phone;?>" placeholder="Introduce tu teléfono">
        </div>

        <div class="form-group">
        <label for="f_message">Consulta <span class="asterisk">*</span></label>
        <textarea id="f_message" name="f_message" rows="7" class="form-control" placeholder="Escribe aquí tu consulta" required aria-required="true"><?php echo $f_message;?></textarea>

        <?php //Comprobamos si hay errores en la validación del campo Mensaje
        if ( is_wp_error( $reg_errors ) ) {
            if ($reg_errors->get_error_message("empty-message")) {?>
            <br class="clearfix" />
            <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p><?php echo $reg_errors->get_error_message("empty-message");?></p>
            </div>
            <?php }
        }?>
        </div>

        <button type="submit" id="btn-submit" name="btn-submit" class="btn btn-default">Solicitar Información</button>
    </form>
</div>