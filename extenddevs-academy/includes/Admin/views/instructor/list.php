<div class="wrap">

    <h1 class="wp-heading-inline"><?php _e('Instructors', 'wedev-academy') ?></h1>
    <a href="<?php echo admin_url('admin.php?page=extenddev-academy&action=new'); ?>" class="page-title-action"><?php _e('Add New', 'wedev-academy') ?></a>
    <hr class="wp-header-end">

    <?php if(isset($_GET['inserted'])){ ?>
        <div class="notice notice-success"><p>Instructor added successfully.</p></div>
    <?php } ?>

    <?php if(isset($_GET['instructor-deleted']) && $_GET['instructor-deleted'] == 'true'){ ?>
        <div class="notice notice-success"><p>Instructor Deleted successfully.</p></div>
    <?php }else if(isset($_GET['instructor-deleted']) && $_GET['instructor-deleted'] == 'false'){ ?>
        <div class="notice notice-error"><p>Instructor Can not be deleted.</p></div>
    <?php } ?>

    <?php

        // echo 'academy-directory -> ' . EXTENDDEVS_ACADEMY_DIR . "<br>";
        // echo 'academy-url ->' . EXTENDDEVS_ACADEMY_URL . "<br>";
        // echo 'academy-version ->' . EXTENDDEVS_ACADEMY_VERSION . "<br>";
        // echo 'academy-file ->' . EXTENDDEVS_ACADEMY_FILE . "<br>";
        // echo 'academy-assets ->' . EXTENDDEVS_ACADEMY_ASSETS . "<br>";
        // echo 'academy-path ->' . EXTENDDEVS_ACADEMY_PATH . "<br>";
        

    
    ?>



    <form action="" method="post">
        <?php

            $table = new ExtendDevs\Academy\Admin\Instructors_List();
            $table->prepare_items();
            $table->display();

        ?>
    </form>

</div>