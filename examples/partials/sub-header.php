<!-- For mini header: add class 'nasa__subsite-section-mini' -->
<nav id="nasa__subsite-section">
    <!-- NASA SUB-SITE NAVIGATION BAR/DROPDOWN -->
    <?= $this->partial("pages/partials/sub-navigation.php"); ?>
    <!-- END NASA SUB-SITE NAVIGATION BAR/DROPDOWN -->

    <!-- NASA SUB-SITE INFORMATION -->
    <div class="container">
        <div class="row">
            <!-- Remember to correct the bootstrap column widths appropriately. -->
            <!-- Default: left. To align right, add class `nasa__sub-name-right` -->
            <div class="nasa__sub-name col-md-3">
                <div><a href="/">Code 690</a></div>
            </div>

            <!-- Default: right. To align left, add class `nasa__sub-logo-left` -->
            <div class="nasa__sub-logo col-md-3">
                <img src="assets/img/emac/emac_logo_cropped.jpg" alt="EMAC" />
            </div>

            <!-- Leave this last so the others are displayed in the right order. -->
            <div class="nasa__sub-name col-md-6">
                <div>
                    <a href="/">
                        <!-- Add a `div` for a subtitle, like so: -->
                        <div>Sciences and Exploration Directorate</div>
                        Solar System Exploration Division
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- END NASA SUB-SITE INFORMATION -->
</nav>