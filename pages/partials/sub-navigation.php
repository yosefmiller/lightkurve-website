<!-- NASA SUB-SITE NAVIGATION BAR -->
<div id="nasa__sub-navigation">
    <div class="container">
        <ul class="nasa__navigation-bar nav navbar-nav navbar-collapse collapse">
            <?php foreach($this->subNavigation as $i => $nav){ ?>
                <li><a class="nasa__sub-navigation-section__<?= $nav["id"] ?>" href="<?= $nav["url"] ?>"><?= $nav["title"] ?></a></li>
            <?php } ?>
        </ul>
    </div>
</div>
<!-- END NASA SUB-SITE NAVIGATION BAR -->

<!-- NASA SUB-SITE NAVIGATION DROPDOWN -->
<?php foreach($this->subNavigation as $nav){ ?>
    <div class="nasa__sub-navigation-dropdown nasa__sub-navigation-section__<?= $nav["id"] ?>">
        <div class="container">
            <div class="col-md-12 hidden visible-xs">
                <div class="nasa__sub-navigation-dropdown-heading">
                    <a href="<?= $nav["url"] ?>">
                        <span>
                            <?= $nav["title"] ?>
                        </span>
                    </a>
                </div>
            </div>
            <?php
            // Compute total number of columns
            $totalNumColumns = 0;
            foreach($nav["branches"] as $branch){
                $totalNumColumns += count($branch["columns"]);
            }

            // Print all branches and compute width of each column
            foreach($nav["branches"] as $branch){
                $thisNumColumns = count($branch["columns"]);
                $thisBranchClass = "col-md-" . (4 * $thisNumColumns);
                if ($totalNumColumns == 2) { $thisBranchClass .= " col-md-push-4"; }
                ?>
                <div class="<?= $thisBranchClass ?>">
                    <div class="nasa__sub-navigation-dropdown-heading">
                        <span>
                            <?= $branch["title"] ?>
                        </span>
                    </div>
                    <?php foreach($branch["columns"] as $col){ ?>
                        <ul class="col-md-<?= (12 / $thisNumColumns) ?>">
                            <?php foreach($col["codes"] as $code){ ?>
                                <li>
                                    <a href="<?= $code["url"] ?>">
                                        <?= $code["title"] ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<!-- END NASA SUB-SITE NAVIGATION DROPDOWN -->

<!-- NASA NAVIGATION COLORS -->
<style>
    <?php foreach($this->subNavigation as $nav){
        echo "#nasa__sub-navigation .nasa__sub-navigation-section__". $nav["id"] .":hover, .nasa__sub-navigation-section__". $nav["id"] ." { background-color: ". $nav["hex_color"] ."; }\n";
    } ?>
</style>
<!-- END NASA NAVIGATION COLORS -->