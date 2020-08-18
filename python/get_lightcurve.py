from helpers import form_data, response
import numpy, write_files
from astropy.visualization import PercentileInterval

def get (search_result):
    # Initialize
    quality_bitmask = form_data.get("quality_bitmask",    type="int",   force=False)
    cutout_size     = form_data.get("cutout_size", (5,5), type="float", force=False)
    data_archive    = form_data.get("data_archive",       type="str")
    target          = form_data.get("target",             type="str")
    is_stitch       = form_data.get("is_stitch",          type="boolean")

    # Prepare arguments
    if data_archive == "search_tesscut":
        args = dict(download_dir="outputs", cutout_size=cutout_size)
    else:
        args = dict(download_dir="outputs", quality_bitmask=quality_bitmask)

    # Download
    print("``Downloading target from archive.")
    file = search_result.download_all(**args) if is_stitch else search_result.download(**args)

    # Retrieve lightcurve
    print("``Converting to lightcurve.")
    if data_archive == 'search_target_pixel':
        # Output header files
        if form_data.get("is_view_metadata", type="boolean"):
            fits_header_0 = file.get_header(ext=0).tostring("<br/>")
            fits_header_1 = file.get_header(ext=1).tostring("<br/>")
            fits_header_2 = file.get_header(ext=2).tostring("<br/>")
            # tpf_model     = str(tpf.get_model().__dict__) #.replace("\n", "<br/>")
            response.add("header_primary",  fits_header_0, True)
            response.add("header_pixels",   fits_header_1, True)
            response.add("header_aperture", fits_header_2, True)
            # response.add("tpf_model",       tpf_model,     True)

        lc = tpf_to_lc(file)

    elif data_archive == 'search_tesscut':
        lc = tpf_to_lc(file)

    elif data_archive == 'search_light_curve':
        flux_type = form_data.get("flux_type", type="str")
        lc = file.SAP_FLUX if flux_type == 'sap' else file.PDCSAP_FLUX

        # Stitch multiple lightcurves together
        if is_stitch:
            lc = lc.stitch()


    # Add additional response data
    response.add("Mission", str(lc.mission), True)

    return lc

def tpf_to_lc (tpf):
    # Photometry Type (Aperture/PRF)
    photometry_type = form_data.get("photometry_type", type="str")
    if photometry_type == "photometry_type_prf":
        method = 'prf'
        print("``Performing PRF photometry.")

        args3 = dict()
    else:
        method = 'aperture'

        # Aperture mask
        aperture_mask = tpf.pipeline_mask
        response.add("aperture_pixel_shape", str(tpf.shape))

        # Custom aperture
        if form_data.get("is_custom_aperture", type="boolean"):
            aperture_type = form_data.get("aperture_type", type="str")

            if aperture_type == "aperture_type_percent":
                aperture_percent = form_data.get("aperture_percent", type="int")
                aperture_mask = numpy.nanmedian(tpf.flux, axis=0) > numpy.nanpercentile(numpy.nanmedian(tpf.flux, axis=0),
                                                                                        aperture_percent)

            elif aperture_type == "aperture_type_manual":
                aperture_rows    = form_data.get("aperture_rows", type="int")
                aperture_columns = form_data.get("aperture_columns", type="int")
                aperture_custom  = form_data.get("aperture_custom", type="str").split(",")
                aperture_custom  = [True if x == "1" else False for x in aperture_custom]
                print("``Parsing aperture list of length ", numpy.shape(aperture_custom),
                      " to ", aperture_rows, " rows and ", aperture_columns, " columns.")
                aperture_mask = numpy.reshape(aperture_custom, (aperture_rows, aperture_columns))[::-1]

            elif aperture_type == "aperture_type_threshold":
                pass

        # Set tpf output data and write flux to file for plotting
        tpf_response = dict(
            target_id   = tpf.targetid,
            img_extent  = (tpf.column, tpf.column + tpf.shape[2], tpf.row, tpf.row + tpf.shape[1]),
            z_limits    = PercentileInterval(95.).get_limits(tpf.flux[0]),
            flux_file   = write_files.target_pixel_file(tpf.flux[0], aperture_mask, tpf.shape)
        )
        response.add("tpf", tpf_response)

        # Set the args
        # todo include parameter centroid_method: str, ‘moments’ or ‘quadratic’
        args3 = dict(aperture_mask=aperture_mask)

    return tpf.to_lightcurve(method=method, **args3)