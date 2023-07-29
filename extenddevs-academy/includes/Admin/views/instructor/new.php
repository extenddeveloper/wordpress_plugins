<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Add Instructor', 'wedev-academy') ?></h1>
    <a href="<?php echo admin_url('admin.php?page=extenddev-academy&action=new'); ?>" class="page-title-action"><?php _e('Add New', 'wedev-academy') ?></a>

    <style>
        .form-invalid input {
            color: red;
            border: 1px solid #c85353;
        }
    </style>
    <form action="" method="post">
        <table class="form-table">
            <tr class="row <?php echo $this->has_error('name') ? 'form-invalid' : ''; ?>">
                <th scope="row">
                    <label for="name">Name: </label>
                </th>
                <td>
                    <input type="text" name="name" class="regular-text" id="name" placeholder="name" value="">
                    <?php if( $this->has_error('name')){ ?>
                        <p class="error"><?php echo $this->get_error('name'); ?></p>
                    <?php } ?>
                </td>
            </tr>
            <tr class="row <?php echo $this->has_error('email') ? 'form-invalid' : ''; ?>">
                <th scope="row">
                    <label for="email">Email: </label>
                </th>
                <td>
                    <input type="email" name="email" class="regular-text" id="email" placeholder="Email" value="">
                    <?php if($this->has_error('email')){ ?>
                        <p class="error"><?php echo $this->get_error('email'); ?></p>
                    <?php } ?>
                </td>
            </tr>
            <tr class="row <?php echo $this->has_error('address') ? 'form-invalid' : ''; ?>">
                <th scope="row">
                    <label for="address">Address: </label>
                </th>
                <td>
                    <textarea cols="30" rows="5" name="address" class="regular-text" id="address" placeholder="Address" value=""></textarea>
                    <?php if($this->has_error('address')){ ?>
                        <p class="error"><?php echo $this->get_error('address'); ?></p>
                    <?php } ?>
                </td>
            </tr>
            <tr class="row <?php echo $this->has_error('phone') ? 'form-invalid' : ''; ?>">
                <th scope="row">
                    <label for="address">Phone: </label>
                </th>
                <td>
                    <input type="tel" name="phone" class="regular-text" id="phone" placeholder="Phone" value="">
                    <?php if($this->has_error('phone')){ ?>
                        <p class="error"><?php echo $this->get_error('phone'); ?></p>
                    <?php } ?>
            </tr>
            <tr>
                <th scope="row"></th>
                <td>
                    <?php 
                        wp_nonce_field('new-instructor');
                        submit_button(__('Add Instructor', 'extenddev-academy'), 'primary', 'submit_instructor'); 
                    ?>
                </td>
            </tr>
        </table>
    </form>

</div>
