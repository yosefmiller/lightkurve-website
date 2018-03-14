<?php
$tools = [
    [
        "name"        => "Atmos",
        "info"        => 'Claire <a target="_blank" href="https://bitbucket.org/ravikopparapu/contributors/wiki/Contributors">et al.</a>',
        "label"       => [
            [ "Atm", "label-warning arrowed-right" ],
            [ "Star", "label-important label-dim arrowed-in" ]
        ],
        "links"       => [
            "about"   => "https://bitbucket.org/ravikopparapu/contributors/wiki/Contributors",
            "install" => "https://github.com/VirtualPlanetaryLaboratory/atmos#install",
        ],
        "description" => "IN PROGRESS: Atmos is a packaged photochemical model and climate model used to understand the vertical structure of various terrestrial atmospheres. Its photochemical model calculates the profiles of various chemicals in the atmosphere, including both gaseous and aerosol phases. Its climate model calculates the temperature profile of the atmosphere. While individually these models may be run for useful information, when coupled they offer a detailed analysis of atmospheric steady-state structures."
    ],
    [
        "name"        => "CCMC Heliophysics Models",
        "info"        => 'SWMF team, Glocer, Usmanov, et al.',
        "logo"        => [
            "src" => "https://emac.gsfc.nasa.gov/images/ccmc_logo.gif",
            "alt" => "CCMC Logo"
        ],
        "label"       => [
            [ "Star", "label-important" ],
        ],
        "links"       => [
            "about"  => "https://ccmc.gsfc.nasa.gov/community/EXO/",
            "launch" => "https://ccmc.gsfc.nasa.gov/requests/GM/exo_user_registration.php"
        ],
        "description" => "In the initial CCMC exoplanet applications adaptation, users are able to view and analyze simulations carried out with three different models: SWMF, PWOM and ALF3D. These simulations are used to demonstrate how heliophysics models hosted at CCMC can be used to explore exoplanetary problems. Please follow the links to individual models for more details and to access the simulation results."
    ],
    [
        "name"        => "Coronagraphic Mission Simulator",
        "info"        => "Arney et al.",
        "label"       => [
            [ "Obs", "label-info arrowed-right" ],
            [ "1D Atm", "label-warning label-dim arrowed-in" ]
        ],
        "links"       => [
            "about"   => "https://github.com/giadasprink/coronagraph#coronagraph",
            "install" => "https://github.com/giadasprink/coronagraph#install",
            "launch"  => "https://coronagraph.emac.gsfc.nasa.gov"
        ],
        "description" => "This simplified coronagraph simulator tool is based on the coronagraph noise model in Robinson et al. 2016, adapted by J. Lustig-Yaeger, G. Arney and J. Tumlinson."
    ],
    [
        "name"        => "Exoplanet Boundaries Calculator",
        "info"        => 'Kopparapu et al.',
        "label"       => [
            [ "Calc", "label-olive" ],
        ],
        "links"       => [
            "launch" => "https://tools.emac.gsfc.nasa.gov/EBC"
        ],
        "description" => 'The Exoplanet Boundaries Calculator (EBC) is an online calculator that provides condensation boundaries (in stellar fluxes) for ZnS, H<sub>2</sub>O, CO<sub>2</sub> and CH<sub>4</sub> for the following planetary radii that represent transition to different planet regimes: 0.5, 1, 1.75, 3.5, 6, and 14.3 R<sub>E</sub>. The purpose is to classify planets into different categories based on a species condensing in a planet\'s atmosphere. These boundaries are applicable only for G-dwarf stars.'
    ],
    [
        "name"        => "PandExo JWST/HST Simulator",
        "info"        => 'Batalha et al.',
        "label"       => [
            [ "Obs", "label-info" ],
        ],
        "links"       => [
            "about"   => "https://natashabatalha.github.io/PandExo/",
            "install" => "https://natashabatalha.github.io/PandExo/installation.html",
            "launch"  => "https://pandexo.emac.gsfc.nasa.gov/"
        ],
        "description" => "PandExo is both an online tool and a Python package for generating instrument simulations of JWST's NIRSpec, NIRCam, NIRISS and NIRCam and HST WFC3. It uses throughput calculations from STScI's Exposure Time Calculator, Pandeia."
    ],
    [
        "name"        => "Planetary Spectrum Generator",
        "info"        => 'Villanueva et al.',
        "label"       => [
            [ "Atm", "label-warning arrowed-right" ],
            [ "RT", "label-success arrowed-in arrowed-right" ],
            [ "Obs", "label-info arrowed-in" ],
        ],
        "links"       => [
            "about"   => "https://psg.gsfc.nasa.gov/about.php",
            "launch"  => "https://psg.gsfc.nasa.gov/"
        ],
        "description" => "The Planetary Spectrum Generator (PSG) is an online tool for synthesizing planetary spectra (atmospheres and surfaces) for a broad range of wavelengths (100 nm to 100 mm, UV/Vis/near-IR/IR/far-IR/THz/sub-mm/Radio) from any observatory (e.g., JWST, ALMA, Keck, SOFIA)."
    ]
];
?>
<div id="tool-list">
    <!-- DESCRIPTION -->
    <div class="col-xs-12">
        <h4 style="font-weight: 600;margin-right: 50px;">Welcome to the GSFC Exoplanet Modeling and Analysis Center (EMAC)</h4>
        <p>EMAC serves as a repository and integration platform for modeling and analysis tools focused on the study of exoplanet characteristics. If you have suggestions for improvements or new applications, please email us at gsfc.emac at gmail.com.</p>
	    <p>EMAC is a Key Project of the GSFC <a href="https://seec.gsfc.nasa.gov">Sellers Exoplanet Environments Collaboration</a> (SEEC). The P.I. is Avi Mandell, and the Deputy P.I. is Eric Lopez; more information on EMAC staffing and organization will be posted shortly.</p>
    </div>

    <!-- SPACER -->
    <div class="col-xs-12" style="height: 15px;"></div>

    <!-- TOOL LIST -->
	<div class="col-xs-12">
        <?php
        foreach ($tools as $tool) {
            $about   = $tool[ 'links' ][ 'about' ] ?? null;
            $install = $tool[ 'links' ][ 'install' ] ?? null;
            $launch  = $tool[ 'links' ][ 'launch' ] ?? null;
            ?>
			<!-- TOOL -->
			<div class="well clearfix">
				<div class="col-md-3 col-xs-9">
					<strong><?= $tool[ 'name' ] ?></strong><br />
					<small><?= $tool[ 'info' ] ?></small>
                    <?php if ($tool[ 'logo' ]) { ?>
						<img class="hidden-xs visible-md visible-lg" style="display:block;padding-top: 10px;width: 100%;max-width: 165px;"
						     src="<?= $tool[ 'logo' ][ 'src' ] ?>"
						     alt="<?= $tool[ 'logo' ][ 'alt' ] ?>" />
                    <?php } ?>
				</div>
				<div class="col-md-2 col-md-push-7 col-xs-3">
                    <?php if ($about) { ?>
						<a target="_blank" href="<?= $about ?>">
							<button type="button" class="btn btn-3d btn-sky text-uppercase btn-sm">About</button>
						</a>
                    <?php } ?>
                    <?php if ($install) { ?>
						<a target="_blank" href="<?= $install ?>">
							<button type="button" class="btn btn-3d btn-fresh text-uppercase btn-sm">Install</button>
						</a>
                    <?php } ?>
					<a target="_blank" href="<?= $launch ?? "javascript:void(0)" ?>" <?= !$launch ? "disabled=''" : "" ?>>
						<button type="button" class="btn btn-3d btn-sunny text-uppercase btn-sm <?= !$launch ? "disabled" : "" ?>">Launch
						</button>
					</a>
				</div>
				<div class="visible-xs visible-sm hidden-md col-xs-12" style="height: 15px;"></div>
				<div class="col-md-7 col-md-pull-2 col-xs-12">
					<p><?= $tool[ 'description' ] ?></p>
					<div>
                        <?php foreach ($tool[ 'label' ] as $label) { ?>
							<span class="label label-large <?= $label[ 1 ] ?>"><?= $label[ 0 ] ?></span>
                        <?php } ?>
					</div>
				</div>
			</div>
        <?php } ?>
	</div>
</div>