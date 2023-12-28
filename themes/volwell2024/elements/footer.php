<?php defined('C5_EXECUTE') or die("Access Denied.");
?>

<footer id="footer-theme">
    <section>
        <div class="container-fluid">
              <div class="row">
                  <div class="card-group">
                    <div class="col-lg-3">
                        <?php
                            $i1 = new GlobalArea('Imagenav1');
                            $i1->display();
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?php
                            $i2 = new GlobalArea('Imagenav2');
                            $i2->display();
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?php
                            $i3 = new GlobalArea('Imagenav3');
                            $i3->display();
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?php
                            $i4 = new GlobalArea('Imagenav4');
                            $i4->display();
                        ?>
                    </div>
                </div>
             </div>
        </div>
    </section>
    <section>
        <div class=" container-fluid p-0">
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<?php View::element('footer_required'); ?>
</body>
</html>
