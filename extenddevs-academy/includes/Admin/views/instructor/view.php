<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Instructor', 'wedev-academy') ?></h1>
    <a href="<?php echo admin_url('admin.php?page=extenddev-academy&action=new'); ?>" class="page-title-action">Add New</a>
    
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
                    <?php echo esc_attr($instructor['name']); ?>
                </td>
            </tr>
            <tr class="row <?php echo $this->has_error('email') ? 'form-invalid' : ''; ?>">
                <th scope="row">
                    <label for="email">Email: </label>
                </th>
                <td>
                    <?php echo esc_attr($instructor['email']); ?>
                </td>
            </tr>
            <tr class="row <?php echo $this->has_error('address') ? 'form-invalid' : ''; ?>">
                <th scope="row">
                    <label for="address">Address: </label>
                </th>
                <td>
                    <?php echo esc_attr($instructor['address']); ?>
                </td>
            </tr>
            <tr class="row <?php echo $this->has_error('phone') ? 'form-invalid' : ''; ?>">
                <th scope="row">
                    <label for="address">Phone: </label>
                </th>
                <td>
                <?php echo esc_attr($instructor['phone']); ?>
            </tr>
        </table>
    </form>

</div>
