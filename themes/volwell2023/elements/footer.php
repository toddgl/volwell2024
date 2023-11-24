<?php defined('C5_EXECUTE') or die("Access Denied.");

$footerSiteTitle = new GlobalArea('Footer Site Title');
$footerSiteTitleBlocks = $footerSiteTitle->getTotalBlocksInArea();

$footerSocial = new GlobalArea('Footer Social');
$footerSocialBlocks = $footerSocial->getTotalBlocksInArea();

$displayFirstSection = $footerSiteTitleBlocks > 0 || $footerSocialBlocks > 0 || $c->isEditMode();
?>

<footer id="footer-theme">
    <?php
    if ($displayFirstSection) {
        ?>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-sm-9">
                        <?php
                        $a = new GlobalArea('Footer Site Title');
                        $a->display();
                        ?>
                    </div>
                    <div class="col-sm-3">
                        <?php
                        $a = new GlobalArea('Footer Social');
                        $a->display();
                        ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
    ?>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $a = new GlobalArea('Footer Legal');
                    $a->display();
                    ?>
                </div>
                <div class="col-sm-3">
                    <?php
                    $a = new GlobalArea('Footer Navigation');
                    $a->display();
                    ?>
                </div>
                <div class="col-sm-3">
                    <?php
                    $a = new GlobalArea('Footer Contact');
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<?php View::element('footer_required'); ?>
</body>
</html>
