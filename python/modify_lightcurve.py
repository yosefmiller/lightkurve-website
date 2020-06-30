from helpers import form_data, response
import numpy, write_files
#import transit_periodogram.py
#from bls import BLS3
from transit_periodogram import transit_periodogram

def modify (lc):
    is_remove_nans     = form_data.get("is_remove_nans",     default=False)
    is_remove_outliers = form_data.get("is_remove_outliers", default=False)
    is_sff_correction  = form_data.get("is_sff_correction",  default=False)
    is_fill_gaps       = form_data.get("is_fill_gaps",       default=False)
    is_periodogram     = form_data.get("is_periodogram",     default=False)
    is_flatten         = form_data.get("is_flatten",         default=False)
    is_fold            = form_data.get("is_fold",            default=False)
    is_bin             = form_data.get("is_bin",             default=False)
    is_normalize       = form_data.get("is_normalize",       default=False)
    is_river_plot      = form_data.get("is_river_plot",      default=False)
    is_cdpp            = form_data.get("is_cdpp",            default=False)

    if is_remove_nans:
        lc = lc.remove_nans()
        print("Removed NaNs.")

    if is_remove_outliers:
        sigma = form_data.get("outlier_sigma", type="float", default=5.0)
        lc = lc.remove_outliers(sigma=sigma)
        print("Removed outliers at sigma = ", sigma, ".")

    if is_sff_correction:
        windows = form_data.get("windows", type="int", default=1)
        lc = lc.to_corrector().correct(windows=windows)
        print("Applied SFF correction at windows = ", windows, ".")

    if is_remove_outliers:
        sigma = form_data.get("outlier_sigma", type="float", default=5.0)
        lc = lc.remove_outliers(sigma=sigma)
        print("Removed outliers at sigma = ", sigma, ".")

    if is_fill_gaps:
        lc = lc.fill_gaps()
        print("Filled gaps.")

    if is_flatten:
        flatten_window      = form_data.get("flatten_window",       type="int", default=101)
        flatten_polyorder   = form_data.get("flatten_polyorder",    type="int", default=2)
        flatten_tolerance   = form_data.get("flatten_tolerance",    type="int", default=5)
        flatten_iterations  = form_data.get("flatten_iterations",   type="int", default=3)
        flatten_sigma       = form_data.get("flatten_sigma",        type="int", default=3)
        if flatten_tolerance is 0:
            flatten_tolerance = None
        if flatten_polyorder >= flatten_window:
            flatten_polyorder = flatten_window - 1
        flatten_args = dict(window_length=flatten_window, polyorder=flatten_polyorder, break_tolerance=flatten_tolerance, niters=flatten_iterations, sigma=flatten_sigma)

        lc, lc_trend = lc.flatten(return_trend=True, **flatten_args)
        print("Flattened at window_length = ", flatten_window, ".")
    else:
        lc_trend = lc.copy()

    if is_fold:
        period = form_data.get("period", type="float", default=False)
        phase  = form_data.get("phase", type="float", default=0.0)

        if not period:
            # todo stop usage of transit_periodogram
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

        lc = lc.fold(period=period, epoch_time=phase)
        print("Folded at period = ", period, " and phase = ", phase, ".")

    if is_bin:
        bin_size  = form_data.get("bin_size", type="float", default=None)
        bin_count = form_data.get("bin_count", type="int", default=None)
        lc = lc.bin(time_bin_size=bin_size, n_bins=bin_count)
        print("Binned at time interval = ", bin_size or '[blank]', " and count = ", bin_count or '[blank]')

    if is_normalize:
        normalize_unit = form_data.get("normalize_unit")
        lc = lc.normalize(unit=normalize_unit)
        print("Normalized lightcurve.")

    if is_river_plot:
        river_plot_period    = form_data.get("river_plot_period",     type='float')
        river_plot_time      = form_data.get("river_plot_time",       type='float', default=None)
        river_plot_points    = form_data.get("river_plot_points",     type='int',   default=1)
        river_plot_phase_min = form_data.get("river_plot_phase_min",  type='float', default=-0.5)
        river_plot_phase_max = form_data.get("river_plot_phase_max",  type='float', default=0.5)
        river_plot_method    = form_data.get("river_plot_method",     default='mean')

        riverplot_axis = lc.plot_river(riverplot_period, epoch_time=riverplot_time, bin_points=riverplot_points, minimum_phase=riverplot_phase_min, maximum_phase=riverplot_phase_max, method=riverplot_method)
        print("River plot is not yet supported.")

    if is_cdpp:
        cdpp_duration   = form_data.get("cdpp_duration",    type='int',   default=13)
        cdpp_window     = form_data.get("cdpp_window",      type='int',   default=101)
        cdpp_polyorder  = form_data.get("cdpp_polyorder",   type='int',   default=2)
        cdpp_sigma      = form_data.get("cdpp_sigma",       type='float', default=5.)

        cdpp_result = lc.estimate_cdpp(transit_duration=cdpp_duration, savgol_window=cdpp_window, savgol_polyorder=cdpp_polyorder, sigma=cdpp_sigma)
        response.add("CDPP Noise Metric", str(cdpp_result) + " ppm", True)

    if is_periodogram:
#        frequencies = float(form_data.get("frequencies") or 1.0)
        print("Computing periodogram.")
        import astropy.units as u
        periodogram = lc.to_periodogram(minimum_period=0.9*u.day, maximum_period=1.2*u.day, oversample_factor=10)
        response.add("period_at_max_power", str(p.period_at_max_power), True)
        response.add("p_power_file",        write_files.periodogram(p))

    response.add("lc_flux_file", write_files.lightcurve(lc, lc_trend))