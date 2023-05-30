    
    <footer class="footer">
        <div class="container">

            <div class="footer_logo">
                <?php echo wp_get_attachment_image(carbon_get_theme_option('footer_logo'), 'full') ?>
            </div>
            <div class="phone-number">
                <a href="tel:<?php echo carbon_get_theme_option('footer_phone') ?>"><?php echo carbon_get_theme_option('footer_phone') ?></a>
            </div>
            <div class="footer-address">
                <?php echo carbon_get_theme_option('footer_address') ?>
            </div>
            <p class="copyright">© <?php echo date('Y') ?> <?php echo carbon_get_theme_option('copyright') ?></p>

        </div>
    </footer>

</body>


<?php wp_footer(); ?>