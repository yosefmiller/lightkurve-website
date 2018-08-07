def find_data_file (form_data):
    # Store data into individual variables
    data_archive       = form_data["data_archive"]
    flux_type          = form_data["flux_type"]
    target             = form_data["target"]
    limiting_factor    = form_data["limiting_factor"]
    quarter_campaign   = form_data["quarter_campaign"]
    quality_bitmask    = form_data["quality_bitmask"]
    cadence            = form_data["cadence"]
    month              = form_data["month"]
    search_radius      = int(form_data["search_radius"])
    limit_targets      = form_data["limit_targets"]

    # cadence/radius
    args = {'cadence': cadence, 'radius': search_radius, 'quality_bitmask': quality_bitmask}
    # quarter/campaign
    if limiting_factor == 'quarter':
        args['quarter'] = int(quarter_campaign)
    elif limiting_factor == 'campaign':
        args['campaign'] = int(quarter_campaign)
    # month
    if cadence == 'short':
#        month = list(map(int, month.split(",")))
        args['month'] = int(month)
    # limit
    if limit_targets.isdigit():
        args['targetlimit'] = int(limit_targets)

    # Retrieve data from archive
    print("Retrieving target from archive.")
    if data_archive == 'kepler_target_pixel':
        from lightkurve import KeplerTargetPixelFile
        tpf = KeplerTargetPixelFile.from_archive(target, **args)
        if isinstance(tpf, list):
            tpf = tpf[0]

        # Aperture mask
        aperture_mask = tpf.pipeline_mask
        print("Aperture pixel shape is ", str(tpf.shape))
        is_custom_aperture = form_data.get("is_custom_aperture", False)
        if is_custom_aperture:
            aperture_type = form_data.get("aperture_type")

            if aperture_type == "aperture_type_percent":
                aperture_percent = int(form_data.get("aperture_percent"))
                aperture_mask = numpy.nanmedian(tpf.flux, axis=0) > numpy.nanpercentile(numpy.nanmedian(tpf.flux, axis=0), aperture_percent)

            elif aperture_type == "aperture_type_manual":
                aperture_rows    = int(form_data.get("aperture_rows"))
                aperture_columns = int(form_data.get("aperture_columns"))
                aperture_custom  = form_data.get("aperture_custom").split(",")
                aperture_custom  = [ True if x == "1" else False for x in aperture_custom ]
                print("Parsing aperture list of length ", numpy.shape(aperture_custom), " to ", aperture_rows, " rows and ", aperture_columns, " columns.")
                aperture_mask    = numpy.reshape(aperture_custom, (aperture_rows, aperture_columns))[::-1]

        # Set tpf output data and write flux to file for plotting
        global response
        response['tpf'] = prepare_tpf_response_data(tpf)
        response['tpf']['flux_file'] = write_tpf_flux_file(form_data["tracking_id"], tpf.flux[0], aperture_mask, tpf.shape)

        return tpf.to_lightcurve(aperture_mask=aperture_mask)

    elif data_archive == 'kepler_light_curve':
        from lightkurve import KeplerLightCurveFile
        lcf = KeplerLightCurveFile.from_archive(target, **args)
        if flux_type == 'pdcsap':
            return lcf.PDCSAP_FLUX
        elif flux_type == 'sap':
            return lcf.SAP_FLUX
        else:
            return lcf

def write_tpf_flux_file (tracking_id, flux, pipeline_mask, shape):
    # Write flux to file for plotting
    tpf_flux_file = "outputs/" + tracking_id + "_tpf_flux.pt"
    pipeline_mask = pipeline_mask.astype(int)
    with open(tpf_flux_file, "w") as f:
        f.write("flux pipeline_mask \n")
        for index in range(shape[1]):
            f.write(','.join([ str(i) for i in flux[index]          ]) + " ")
            f.write(','.join([ str(i) if i == True else str(numpy.nan) for i in pipeline_mask[index] ]) + "\n")
        f.close()

    print("Wrote tpf flux file to "+tpf_flux_file)
    return tpf_flux_file

def prepare_tpf_response_data (tpf):
    data = {
        'target_id': tpf.targetid,
        'img_extent': (tpf.column, tpf.column + tpf.shape[2], tpf.row, tpf.row + tpf.shape[1]),
        'z_limits': PercentileInterval(95.).get_limits(tpf.flux[0])
    }
    print("Prepared tpf response data")
    return data

def write_lc_flux_file (tracking_id, lc):
    # Limit the number of values to plot
    limit = 10000
    size = numpy.shape(lc.time)[0]
    rate = int(numpy.round(size/limit)) if size > limit else 1
    indexes = numpy.arange(0, size, rate)
    print("Limiting LC flux file results to ", limit, " values.")

    # Write flux to file for plotting
    lc_flux_file = "outputs/" + tracking_id + "_lc_flux.pt"
    with open(lc_flux_file, "w") as f:
        f.write("time flux flux_err \n")
        for index in indexes:
            f.write(str(numpy.round( lc.time[index],     5 )) + " " +
                    str(numpy.round( lc.flux[index],     5 )) + " " +
                    str(numpy.round( lc.flux_err[index], 8 )) + "\n")
        f.close()

    print("Wrote LC flux file to "+lc_flux_file)
    return lc_flux_file

def write_p_power_file (tracking_id, p):
    # Limit the number of values to plot
    limit = 5000
    size = numpy.shape(p.frequencies)[0]
    rate = int(numpy.round(size/limit)) if size > limit else 1
    indexes = numpy.arange(0, size, rate)
    #indexes = numpy.arange(0, 10000, 1)
    print("Limiting Periodogram power file results to ", limit, " values.")

    # Write power to file for plotting
    p_power_file = "outputs/" + tracking_id + "_p_power.pt"
    with open(p_power_file, "w") as f:
        f.write("frequencies _ power _ _ _ \n")
        for index in indexes:
            f.write(str(numpy.round( p.frequencies[index], 3 )) + " " +
                    str(numpy.round( p.powers[index],      2 )) + "\n")
            #f.write(str(numpy.round(re.search(r"[-+]?\d*\.\d+|\d+", str(p.frequencies[index] ))[0])) + " " +
            #                    str(numpy.round(re.search(r"[-+]?\d*\.\d+|\d+", str(p.powers[index]      ))[0])) + "\n")
        f.close()

    print("Wrote Periodogram power file to "+p_power_file)
    return p_power_file

def modify_lightcurve (form_data, lc):
    is_remove_nans     = form_data.get("is_remove_nans",     False)
    is_remove_outliers = form_data.get("is_remove_outliers", False)
    is_sff_correction  = form_data.get("is_sff_correction",  False)
    is_fill_gaps       = form_data.get("is_fill_gaps",       False)
    is_periodogram     = form_data.get("is_periodogram",     False)
    is_flatten         = form_data.get("is_flatten",         False)
    is_fold            = form_data.get("is_fold",            False)
    is_bin             = form_data.get("is_bin",             False)

    if is_remove_nans:
        lc = lc.remove_nans()
        print("Removed NaNs.")

    if is_remove_outliers:
        sigma = float(form_data.get("sigma") or 5.0)
        lc = lc.remove_outliers(sigma=sigma)
        print("Removed outliers at sigma = ", sigma, ".")

    if is_sff_correction:
        windows = int(form_data.get("windows") or 1)
        lc = lc.correct(windows=windows)
        print("Applied SFF correction at windows = ", windows, ".")

    if is_remove_outliers:
        sigma = float(form_data.get("sigma") or 5.0)
        lc = lc.remove_outliers(sigma=sigma)
        print("Removed outliers at sigma = ", sigma, ".")

    if is_fill_gaps:
        lc = lc.fill_gaps()
        print("Filled gaps.")

    if is_flatten:
        window_length = int(form_data.get("window_length") or 101)
        lc = lc.flatten(window_length=window_length)
        print("Flattened at window_length = ", window_length, ".")

    if is_fold:
        period = form_data.get("period")
        phase  = float(form_data.get("phase") or 0.0)

        if not period:
            print("Computing best fit using transit periodogram. This may take some time.")
            periods = numpy.arange(0.3, 1.5, 0.0001)
            durations = numpy.arange(0.005, 0.15, 0.001)
            power, _, _, _, _, _, _ = transit_periodogram(time=lc.time,
                                                          flux=lc.flux,
                                                          flux_err=lc.flux_err,
                                                          periods=periods,
                                                          durations=durations)
            period = periods[numpy.argmax(power)]
            print('Best Fit Period: {} days'.format(period))

        lc = lc.fold(period=float(period), phase=phase)
        print("Folded at period = ", period, " and phase = ", phase, ".")

    if is_bin:
        bin_size   = int(form_data.get("bin_size") or 13)
        bin_method = form_data.get("bin_method") or "mean"
        lc = lc.bin(binsize=bin_size, method=bin_method)
        print("Binned at binsize = ", bin_size, " and method = ", bin_method)

    lc = lc.normalize()

    if is_periodogram:
#        frequencies = float(form_data.get("frequencies") or 1.0)
        print("Computing periodogram.")
        return lc, lc.periodogram()
    else:
        return lc, False




########## LIGHTKURVE SCRIPT BEGIN ##########

print ("Beginning script execution...\n")

# Ignore numpy version warning
import warnings
warnings.filterwarnings("ignore", message="numpy.dtype size changed")
warnings.filterwarnings("ignore", message="Using a non-tuple sequence")

# Import scripts
import sys, json, warnings, numpy
from astropy.visualization import PercentileInterval
#import transit_periodogram.py
#from bls import BLS
from transit_periodogram import transit_periodogram

# Load json data
form_data = json.loads(sys.argv[1])
tracking_id = form_data["tracking_id"]

# Prepare response
response = {}
response["input"] = form_data
response["status"] = "success"

print("Finished initialization.")

########## CALCULATION BEGIN ##########

# Find data file
lc = find_data_file(form_data)
print("Found data file.")

# Output flux plot data
lc_modified, p = modify_lightcurve(form_data, lc)
response["lc_flux_file"] = write_lc_flux_file(form_data["tracking_id"], lc_modified)
if p:
    response["p_power_file"] = write_p_power_file(form_data["tracking_id"], p)

# Additional response data
response["cdpp"] = str(lc.cdpp())
response["mission"] = str(lc.mission)

########## CALCULATION END ##########

# Save data
response = json.dumps(response, indent=2)
output_file_name = "outputs/" + tracking_id + "_response.json"
with open(output_file_name, "w") as f:
    f.write(response)

print ("\nFinished execution!")

########## LIGHTKURVE SCRIPT END ##########