
<div id="extenddevs-enquiry-form" class="extenddevs-enquiry-form">

    <form action="" method="post">
        <div class="form-group">
            <label for="name"><?php _e('Name', 'extenddev-academy');  ?></label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email"><?php _e('Email', 'extenddev-academy');  ?></label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="message"><?php _e('Message', 'extenddev-academy');  ?></label>
            <textarea class="form-control" id="message" name="message" required></textarea>
        </div>
        <div class="form-group">

            <?php wp_nonce_field('wd_enquiry_nonce') ?>

            <input type="hidden" name="action" value="wd_enquiry_form_submit">
            <input type="submit" name="send-enquiry" value="<?php _e('Submit', 'extenddev-academy');  ?>" class="btn btn-primary">

        </div>
    </form>

</div>