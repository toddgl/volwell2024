<?php defined('C5_EXECUTE') or die("Access Denied.");

$footerSiteTitle = new GlobalArea('Footer Site Title');
$footerSiteTitleBlocks = $footerSiteTitle->getTotalBlocksInArea();

$footerSocial = new GlobalArea('Footer Social');
$footerSocialBlocks = $footerSocial->getTotalBlocksInArea();

$displayFirstSection = $footerSiteTitleBlocks > 0 || $footerSocialBlocks > 0 || $c->isEditMode();
?>

<footer id="footer-theme">
    <section>
        <div class="container-fluid"> 
            <div class="row vw-footer-bar">
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <?php
                    $a = new GlobalArea('Logo');
                    $a->display();
                    ?>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <?php
                    $a = new GlobalArea('Volunteer Navigation');
                    $a->display();
                    ?>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <?php
                    $a = new GlobalArea('Member Navigation');
                    $a->display();
                    ?>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <?php
                    $a = new GlobalArea('About Navigation');
                    $a->display();
                    ?>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <?php
                    $a = new GlobalArea('Footer Contact');
                    $a->display();
                    ?>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <?php
                    $a = new GlobalArea('Social Media Contact');
                    $a->display();
                    ?>
                </div>
            </div>
        </div>
    </section>
</footer>

<footer id="concrete5-brand">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <span><?php echo t('Built with <a href="http://www.concrete5.org" class="concrete5" rel="nofollow">concrete5</a> CMS.') ?></span>
            </div>
        </div>
    </div>
</footer>

</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<?php View::element('footer_required'); ?>
</body>
</html>
