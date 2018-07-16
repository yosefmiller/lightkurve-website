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
        foreach ($this->toolList as $tool) {
            $about   = $tool[ 'links' ][ 'about' ] ?? null;
            $install = $tool[ 'links' ][ 'install' ] ?? null;
            $launch  = $tool[ 'links' ][ 'launch' ] ?? null;
            ?>
			<!-- TOOL -->
			<div class="well clearfix">
                <?php if ($tool[ 'logo' ]) { ?>
					<img class="visible hidden-xs entry_logo" src="<?= $tool[ 'logo' ][ 'src' ] ?>" alt="<?= $tool[ 'logo' ][ 'alt' ] ?>" />
                <?php } ?>
				<p class="entry_name"><?= $tool[ 'name' ] ?></p>
				<p class="entry_info">
                    <?= $tool[ 'info' ] ?>
                    <?php foreach ($tool[ 'label' ] as $label) { ?>
						<span class="label label-large <?= $label[ 1 ] ?>"><?= $label[ 0 ] ?></span>
                    <?php } ?>
				</p>
				<p class="entry_description"><?= $tool[ 'description' ] ?></p>
				<div class="text-right">
					<a target="_blank" href="<?= $launch ?? "javascript:void(0)" ?>" <?= !$launch ? "disabled=''" : "" ?>>
						<button type="button" class="btn btn-hollow btn-hollow-red <?= !$launch ? "disabled" : "" ?>">Launch
						</button>
					</a>
                    <?php if ($install) { ?>
						<a target="_blank" href="<?= $install ?>">
							<button type="button" class="btn btn-hollow btn-hollow-green">Install</button>
						</a>
                    <?php } ?>
                    <?php if ($about) { ?>
						<a target="_blank" href="<?= $about ?>">
							<button type="button" class="btn btn-hollow btn-hollow-blue">About</button>
						</a>
                    <?php } ?>
				</div>
			</div>
        <?php } ?>
	</div>
</div>
<style>
	#tool-list .well {
		background: linear-gradient(to left, #fefeff, #f0f0f0);
		border: 1px solid rgba(12, 12, 12, 0.2);
	}
	
	#tool-list p {
		line-height: 1.6;
		margin-bottom: 16px;
	}
	
	#tool-list p.entry_name {
		font-size: 12pt;
		font-weight: bold;
		color: #4d4d4d;
		margin-bottom: 0;
		padding-bottom: 0;
	}
	
	#tool-list p.entry_info {
		font-size: 10pt;
		color: #4d4d4d;
		margin-top: 0;
		padding-top: 4pt;
	}
	
	#tool-list p.entry_description {
		font-size: 11pt;
		color: #4d4d4d;
		text-align: justify;
	}
	
	#tool-list img.entry_logo {
		width: 100%;
		max-width: 150px;
		float: right;
	}
	
	.btn-hollow {
		font-weight: bold;
		text-transform: uppercase;
		font-size: 14px;
		background-color: transparent !important;
		padding: 11px 15px;
		line-height: 16px;
		border-radius: 3px;
		border: 1px solid; #ccc;
		outline: 0 !important;
		margin-right: 8px;
		margin-bottom: 15px;
	}
	.btn-hollow.disabled {
		color: gray !important;
		border-color: gray !important;
	}
	.btn-hollow-blue {
		color: #1779ba;
		border-color: #1779ba;
	}
	.btn-hollow-green {
		color: #34ba5d;
		border-color: #34ba5b;
	}
	.btn-hollow-red {
		color: #cc4b37;
		border-color: #cc4b37;
	}
	.btn-hollow-blue:hover, .btn-hollow-blue:focus, .btn-hollow-blue:active, .btn-hollow-blue:active:focus {
		color: #0c3d5d;
		border-color: #0c3d5d;
	}
	.btn-hollow-green:hover, .btn-hollow-green:focus, .btn-hollow-green:active, .btn-hollow-green:active:focus {
		color: #157539;
		border-color: #157539;
	}
	.btn-hollow-red:hover, .btn-hollow-red:focus, .btn-hollow-red:active, .btn-hollow-red:active:focus {
		color: #67251a;
		border-color: #67251a;
	}
</style>