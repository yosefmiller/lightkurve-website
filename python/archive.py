import lightkurve, numpy, write_files, response
from astropy.visualization import PercentileInterval

def find_data_file (form_data):
    # Prepare argument list
    data_archive = form_data["data_archive"]
    target       = form_data["target"]
    args, args2  = prepare_args(form_data)

    # Retrieve data from archive
    print("Retrieving target from archive.")

    if data_archive == 'kepler_target_pixel':
        return import_kepler_target_pixel(form_data, target, args, args2)

    elif data_archive == 'kepler_light_curve':
        return import_kepler_light_curve(form_data, target, args, args2)


def prepare_tpf_response_data (tpf):
    data = {
        'target_id': tpf.targetid,
        'img_extent': (tpf.column, tpf.column + tpf.shape[2], tpf.row, tpf.row + tpf.shape[1]),
        'z_limits': PercentileInterval(95.).get_limits(tpf.flux[0])
    }
    return data


def prepare_args (form_data):
    # Store data into individual variables
    limiting_factor  = form_data["limiting_factor"]
    quarter_campaign = form_data["quarter_campaign"]
    quality_bitmask  = form_data["quality_bitmask"]
    cadence          = form_data["cadence"]
    month            = form_data["month"]
    search_radius    = float(form_data["search_radius"])
    limit_targets    = form_data["limit_targets"]
    #mission          = form_data["mission"]

    # Cadence/radius
    args  = {'cadence': cadence, 'radius': search_radius}
    args2 = {'quality_bitmask': quality_bitmask}

    # Mission (int, list of ints)
    #args['mission'] = mission

    # Kepler Quarter / K2 campaign / TESS sector (int, list of ints)
    if quarter_campaign != 'all':
        quarter_campaign = int(quarter_campaign)
    if limiting_factor == 'quarter':
        args['quarter'] = quarter_campaign
    elif limiting_factor == 'campaign':
        args['campaign'] = quarter_campaign
    elif limiting_factor == 'sector':
        args['sector'] = quarter_campaign

    # Month
    if cadence == 'short':
        # month = list(map(int, month.split(",")))
        args['month'] = int(month)

    # Limit
    if limit_targets.isdigit():
        args['limit'] = int(limit_targets)

    return args, args2


def import_kepler_target_pixel (form_data, target, args, args2):
    # Download target pixel file
    tpf = lightkurve.search_targetpixelfile(target, **args).download(**args2)
    if isinstance(tpf, list):
        tpf = tpf[0]

    # Output header files
    if form_data.get("is_view_metadata", False):
        fits_header = tpf.header.tostring("<br/>")
        response.add("headers", fits_header, True)

    # Photometry Type (Aperture/PRF)
    photometry_type = form_data["photometry_type"]
    if photometry_type == "photometry_type_prf":
        method = 'prf'
        print("Performing PRF photometry.")

        args3 = dict()
    else:
        method = 'aperture'

        # Aperture mask
        aperture_mask = tpf.pipeline_mask
        response.add("aperture_pixel_shape", str(tpf.shape))

        is_custom_aperture = form_data.get("is_custom_aperture", False)
        if is_custom_aperture:
            aperture_type = form_data.get("aperture_type")

            if aperture_type == "aperture_type_percent":
                aperture_percent = int(form_data.get("aperture_percent"))
                aperture_mask = numpy.nanmedian(tpf.flux, axis=0) > numpy.nanpercentile(numpy.nanmedian(tpf.flux, axis=0),
                                                                                        aperture_percent)

            elif aperture_type == "aperture_type_manual":
                aperture_rows    = int(form_data.get("aperture_rows"))
                aperture_columns = int(form_data.get("aperture_columns"))
                aperture_custom  = form_data.get("aperture_custom").split(",")
                aperture_custom  = [True if x == "1" else False for x in aperture_custom]
                print("Parsing aperture list of length ", numpy.shape(aperture_custom),
                      " to ", aperture_rows, " rows and ", aperture_columns, " columns.")
                aperture_mask = numpy.reshape(aperture_custom, (aperture_rows, aperture_columns))[::-1]

        # Set tpf output data and write flux to file for plotting
        tpf_response = prepare_tpf_response_data(tpf)
        tpf_response['flux_file'] = write_files.tpf_flux(form_data["tracking_id"], tpf.flux[0], aperture_mask, tpf.shape)
        response.add("tpf", tpf_response)

        # Set the args
        # todo include parameter centroid_method: str, ‘moments’ or ‘quadratic’
        args3 = dict(aperture_mask=aperture_mask)

    return tpf.to_lightcurve(method=method, **args3)


def import_kepler_light_curve (form_data, target, args, args2):
    lcf = lightkurve.search_lightcurvefile(target, **args).download(**args2)

    flux_type = form_data["flux_type"]
    if flux_type == 'pdcsap':
        lcf = lcf.PDCSAP_FLUX
    elif flux_type == 'sap':
        lcf = lcf.SAP_FLUX

    return lcf