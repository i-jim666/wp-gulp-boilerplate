<?php
    $data = getData();

    $header_button_1_text = carbon_get_theme_option('header_btn_1_text');
    $header_button_1_url = carbon_get_theme_option('header_btn_1_url');

    $header_button_2_text = carbon_get_theme_option('header_btn_2_text');
    $header_button_2_url = carbon_get_theme_option('header_btn_2_url');
?>

<div class="hero">
    <div class="bg">
        <img src="<?php echo IMG.'/hero_bg.png' ?>" alt="Background image">
    </div>
    <div class="container">

        <div class="title_content">
            <h1><?php echo $data['title'] ?></h1>
            <div class="desc">
                <?php echo $data['desc'] ?>
            </div>
            
            <?php if(!empty($data['btn_url'])): ?>
                <a href="<?php echo $data['btn_url'] ?>" class="btn primary-btn hide-on-tabs"><?php echo $data['btn_title'] ?></a>
            <?php endif ?>

            <?php if( !empty($header_button_1_url) || !empty($header_button_2_url) ): ?>
                <div class="buttons hide-above-tabs">

                    <?php if(!empty($header_button_1_url)): ?>
                        <a href="<?php echo $header_button_1_url ?>" class="btn ghost-btn"><?php echo $header_button_1_text ?></a>
                    <?php endif ?>

                    <?php if(!empty($header_button_2_url)): ?>
                        <a href="<?php echo $header_button_2_url ?>" class="btn primary-btn"><?php echo $header_button_2_text ?></a>
                    <?php endif ?>

                </div>
            <?php endif ?>

        </div>
        <div class="img">
            <?php echo wp_get_attachment_image($data['image'], 'full') ?>
        </div>

    </div>
</div>