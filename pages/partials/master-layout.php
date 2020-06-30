<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $this->escape($this->pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="/" />
    <link href="img/nasa_favicon.ico" rel="icon">
    <link href="css/vendor/bootstrap.min.css" rel="stylesheet">
    <link href="css/vendor/select2.min.css" rel="stylesheet">
    <link href="css/vendor/select2-bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/layout.css" rel="stylesheet">
    <link href="css/components.css" rel="stylesheet">
    <link href="css/site.css" rel="stylesheet">
    <?php if ($this->isForm) { ?>
        <link href="css/form.css" rel="stylesheet">
        <link href="css/vendor/bootstrapValidator.min.css" rel="stylesheet">
        <link href="css/vendor/icheck-bootstrap.min.css" rel="stylesheet">
    <?php } ?>
</head>
<body>

<!-- Include JS Libraries -->
<script type="text/javascript" src="js/vendor/jquery.min.js"></script>
<script type="text/javascript" src="js/vendor/bootstrap.min.js"></script>
<script type="text/javascript" src="js/vendor/select2.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<?php if ($this->isForm) { ?>
    <script type="text/javascript" src="js/vendor/bootstrapValidator.min.js"></script>
    <script type="text/javascript" src="js/calculation.js"></script>
    <script defer type="text/javascript" src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<?php } if ($this->customJSFile) { ?>
	<script type="text/javascript" src="<?= $this->customJSFile ?>"></script>
<?php } ?>

<!-- MAIN NASA HEADER -->
<?= ($this->isMiniHeader || $this->isHiddenMainNav) ? "" : $this->partial("pages/partials/main-header.php"); ?>
<!-- END MAIN NASA HEADER -->

<!-- NASA SUB-SITE INFO -->
<nav id="nasa__subsite-section" <?= $this->isMiniHeader ? "class='nasa__subsite-section-mini'" : ""; ?>>
	<!-- NASA SUB-SITE NAVIGATION BAR/DROPDOWN -->
    <?= $this->isMiniHeader || $this->isHiddenSubNav ? "" : $this->partial("pages/partials/sub-navigation.php"); ?>
	
	<!-- NASA SUB-SITE INFORMATION -->
	<?= $this->partial("pages/partials/sub-header.php"); ?>
</nav>
<!-- END NASA SUB-SITE INFO -->

<!-- MAIN PAGE CONTENT -->
<section id="nasa__main-section">
    <div class="container">
        <div class="row">
            <?php if (!$this->isHiddenSidebar) { ?>
            <!-- SIDEBAR TRIGGER -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nasa__main-sidebar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- SIDEBAR -->
            <div id="nasa__main-sidebar" class="navbar-collapse collapse col-sm-3 col-sm-push-9 col-md-push-0">
                <div class="row">
                    <div class="col-md-12">
                        <?= $this->partial($this->sidebarPath ?: "pages/partials/sidebar.php"); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!-- MAIN CONTENT -->
            <div id="nasa__main-content" class="<?= (!$this->isHiddenSidebar) ? "col-sm-9 col-sm-pull-3 col-md-pull-0" : "col-sm-12"; ?>">
                <div class="row">
                    <!-- INSERT CONTENT HERE -->
                    <?= $this->yieldview(); ?>
                    <!-- END INSERT CONTENT HERE -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END MAIN PAGE CONTENT -->

<!-- FOOTER -->
<div class="footer">
    <div class="container">
        <?= $this->partial("pages/partials/footer.php"); ?>
    </div>
</div>
<!-- END FOOTER -->

</body>
</html>