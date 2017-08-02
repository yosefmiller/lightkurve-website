<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $this->escape($this->pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="/nasa/template/" />
    <link href="img/nasa_favicon.ico" rel="icon">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/select2.min.css" rel="stylesheet">
    <link href="css/select2-bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/division/site.css" rel="stylesheet">
</head>
<body>

<!-- Include JS Libraries -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/main.js"></script>

<!-- MAIN NASA HEADER -->
<?= $this->partial("pages/partials/main-header.php"); ?>
<!-- END MAIN NASA HEADER -->

<!-- NASA SUB-SITE INFO -->
<?= $this->partial("pages/partials/sub-header.php"); ?>
<!-- END NASA SUB-SITE INFO -->

<!-- MAIN PAGE CONTENT -->
<section id="nasa__main-section">
    <div class="container">
        <div class="row">
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
                        <?= $this->partial("pages/partials/sidebar.php"); ?>
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div id="nasa__main-content" class="col-sm-9 col-sm-pull-3 col-md-pull-0">
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