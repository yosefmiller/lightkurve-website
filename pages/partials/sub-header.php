<!-- For mini header: add class 'nasa__subsite-section-mini' -->
<nav id="nasa__subsite-section" <?= $this->isMiniHeader ? "class='nasa__subsite-section-mini'" : ""; ?>>
    <!-- NASA SUB-SITE NAVIGATION BAR/DROPDOWN -->
    <?= $this->isMiniHeader ? "" : $this->partial("pages/partials/sub-navigation.php"); ?>
    <!-- END NASA SUB-SITE NAVIGATION BAR/DROPDOWN -->

    <!-- NASA SUB-SITE INFORMATION -->
    <div class="container">
        <div class="row">
            <div class="nasa__sub-logo nasa__sub-logo-left col-md-3">
                <img src="img/emac/emac_logo_cropped.jpg" alt="EMAC" />
            </div>
            <div class="nasa__sub-name col-md-9">
                <div>
                    <a href="https://emac.gsfc.nasa.gov/">
                        Exoplanet Modeling and Analysis Center (EMAC)
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- END NASA SUB-SITE INFORMATION -->
</nav>