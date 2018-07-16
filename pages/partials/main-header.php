<nav id="nasa__main-navigation" class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".nasa__navigation-bar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- Logo -->
            <div class="navbar-brand">
                <a class="navbar-brand_logo" href="https://www.nasa.gov/">
                    <img src="img/nasa_header_logo.png" alt="NASA Logo, National Aeronautics and Space Administration">
                </a>
                <div class="navbar-brand_heading">
                    <a style="font-size: 18px;" href="https://www.nasa.gov/">National Aeronautics and Space Administration</a>
                    <a style="font-size: 16px;" href="https://www.nasa.gov/goddard/">Goddard Space Flight Center</a>
                </div>
            </div>
        </div>
        <div id="navbar__main" class="nasa__navigation-bar navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <form class="navbar-form navbar-form-search" role="search" method="get" action="https://www.google.com/search">
                        <div id="search-input-container">
                            <div class="search-input-group">
                                <button type="button" class="btn btn-default" id="hide-search-input-container"><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></button>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="q" placeholder="Search for...">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="hq" value="intitle:EMAC ">
                        <button type="submit" class="btn btn-default" id="search-button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                    </form>
                </li>
                <li class="active hidden-sm"><a href="/">Home</a></li>
                <li><a href="https://science.gsfc.nasa.gov">GSFC Sciences and Exploration</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Simulation Tools <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="https://coronagraph.emac.gsfc.nasa.gov">Coronagraphic Mission Simulator</a></li>
                        <li><a href="https://pandexo.emac.gsfc.nasa.gov">PandExo JWST/HST Simulator</a></li>
                        <li><a href="https://ssed.gsfc.nasa.gov/psg/">Planetary Spectrum Generator</a></li>
                        <li><a href="https://ccmc.gsfc.nasa.gov/community/EXO/">CCMC Heliophysics Models</a></li>
                        <!--<li class="divider"></li>-->
                        <!--<li class="dropdown-header">Nav header</li>-->
                        <!--<li><a href="#">Separated link</a></li>-->
                        <!--<li><a href="#">One more separated link</a></li>-->
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>