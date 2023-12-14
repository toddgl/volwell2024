<?php defined('C5_EXECUTE') or die("Access Denied.");

$footerSiteTitle = new GlobalArea('Footer Site Title');
$footerSiteTitleBlocks = $footerSiteTitle->getTotalBlocksInArea();

$footerSocial = new GlobalArea('Footer Social');
$footerSocialBlocks = $footerSocial->getTotalBlocksInArea();

$displayFirstSection = $footerSiteTitleBlocks > 0 || $footerSocialBlocks > 0 || $c->isEditMode();
?>

<footer id="footer-theme">
    <section>
        <div class="imagenav_block container-fluid text-center">
            <div class="imagenav row">
                <div class="col-lg-3 imagenav-link">
                    <?php
                        $i1 = new GlobalArea('Imagenav1');
                        $i1->display();
                    ?>
                </div>
                <div class="col-lg-3 imagenav-link">
                    <?php
                        $i2 = new GlobalArea('Imagenav2');
                        $i2->display();
                    ?>
                </div>
                <div class="col-lg-3 imagenav-link">
                    <?php
                        $i3 = new GlobalArea('Imagenav3');
                        $i3->display();
                    ?>
                </div>
                <div class="col-lg-3 imagenav-link">
                    <?php
                        $i4 = new GlobalArea('Imagenav4');
                        $i4->display();
                    ?>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container-fluid">
            <div class="row vw-footer-bar text-bg-secondary">
                <div class="footer_block col-lg-2 col-md-2 col-sm-2">
                    <?php
                    $aLogo = new GlobalArea('Logo');
                    $aLogo->display();
                    ?>
                </div>
                <div class="footer_block col-lg-2 col-md-2 col-sm-2 link-light">
                    <?php
                    $aVol = new GlobalArea('Volunteer Navigation');
                    $aVol->display();
                    ?>
                </div>
                <div class="footer_block col-lg-2 col-md-2 col-sm-2 link-light">
                    <?php
                    $aMember = new GlobalArea('Member Navigation');
                    $aMember->display();
                    ?>
                </div>
                <div class="footer_block col-lg-2 col-md-2 col-sm-2 link-light">
                    <?php
                    $aAbout = new GlobalArea('About Navigation');
                    $aAbout->display();
                    ?>
                </div>
                <div class="footer_block col-lg-2 col-md-2 col-sm-2">
                    <?php
                    $aContact = new GlobalArea('Footer Contact');
                    $aContact->display();
                    ?>
                </div>
                <div class="footer_block col-lg-2 col-md-2 col-sm-2">
                    <?php
                    $aSocial = new GlobalArea('Social Media Contact');
                    $aSocial->display();
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
                <span><?php echo ('Built with <a href="http://www.concrete5.org" class="concrete5" rel="nofollow">concrete5</a> CMS.') ?></span>
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
