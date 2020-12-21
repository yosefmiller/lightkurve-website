from helpers import form_data, response
import numpy, write_files
#import transit_periodogram.py
#from bls import BLS3
from transit_periodogram import transit_periodogram

def modify (lc):
    is_remove_nans     = form_data.get("is_remove_nans",     type="boolean")
    is_remove_outliers = form_data.get("is_remove_outliers", type="boolean")
    is_sff_correction  = form_data.get("is_sff_correction",  type="boolean")
    is_fill_gaps       = form_data.get("is_fill_gaps",       type="boolean")
    is_flatten         = form_data.get("is_flatten",         type="boolean")
    is_fold            = form_data.get("is_fold",            type="boolean")
    is_bin             = form_data.get("is_bin",             type="boolean")
    is_normalize       = form_data.get("is_normalize",       type="boolean")
    is_periodogram     = form_data.get("is_periodogram",     type="boolean")
    is_seismology      = form_data.get("is_seismology",      type="boolean")
    is_river_plot      = form_data.get("is_river_plot",      type="boolean")
    is_cdpp            = form_data.get("is_cdpp",            type="boolean")

    lc_seismology = lc.copy() if is_seismology and not is_periodogram else None

    if is_remove_nans:
        lc = lc.remove_nans()
        print("``Removed NaNs.")

    if is_remove_outliers:
        sigma = form_data.get("outlier_sigma", type="float", default=5.0)
        lc = lc.remove_outliers(sigma=sigma)
        print("``Removed outliers at sigma = ", sigma, ".")

    if is_sff_correction:
        windows = form_data.get("sff_windows", type="int", default=1)
        lc = lc.to_corrector().correct(windows=windows)
        print("``Applied SFF correction at windows = ", windows, ".")

    if is_remove_outliers:
        sigma = form_data.get("outlier_sigma", type="float", default=5.0)
        lc = lc.remove_outliers(sigma=sigma)
        print("``Removed outliers at sigma = ", sigma, ".")

    if is_fill_gaps:
        lc = lc.fill_gaps()
        print("``Filled gaps.")

    if is_flatten:
        flatten_window      = form_data.get("flatten_window",     type="int", default=101)
        flatten_polyorder   = form_data.get("flatten_polyorder",  type="int", default=2)
        flatten_tolerance   = form_data.get("flatten_tolerance",  type="int", default=5)
        flatten_iterations  = form_data.get("flatten_iterations", type="int", default=3)
        flatten_sigma       = form_data.get("flatten_sigma",      type="int", default=3)
        if flatten_tolerance is 0:
            flatten_tolerance = None
        if flatten_polyorder >= flatten_window:
            flatten_polyorder = flatten_window - 1
        flatten_args = dict(window_length=flatten_window, polyorder=flatten_polyorder, break_tolerance=flatten_tolerance, niters=flatten_iterations, sigma=flatten_sigma)

        lc, lc_trend = lc.flatten(return_trend=True, **flatten_args)
        print("``Flattened at window_length = ", flatten_window, ".")
    else:
        lc_trend = lc.copy()

    if is_fold:
        period = form_data.get("fold_period", type="float", default=False)
        phase  = form_data.get("fold_phase",  type="float", default=0.0)

        if not period:
            # todo stop usage of transit_periodogramf
            print("``Computing best fit using transit periodogram. This may take some time.")
            periods = numpy.arange(0.3, 1.5, 0.0001)
            durations = numpy.arange(0.005, 0.15, 0.001)
            power, _, _, _, _, _, _ = transit_periodogram(time=lc.time,
                                                          flux=lc.flux,
                                                          flux_err=lc.flux_err,
                                                          periods=periods,
                                                          durations=durations)
            period = periods[numpy.argmax(power)]
            print('``Best Fit Period: {} days'.format(period))

        lc = lc.fold(period=period, t0=phase)
        print("``Folded at period = ", period, " and phase = ", phase, ".")

    if is_bin:
        bin_size   = form_data.get("bin_size",   type="int", default=None)
        bin_count  = form_data.get("bin_count",  type="int", default=None)
        bin_method = form_data.get("bin_method", type="str", default="mean")
        if bin_count:
            bin_size = None
        lc = lc.bin(binsize=bin_size, bins=bin_count, method=bin_method)
        print("``Binned at cadence size = ", bin_size or '[blank]', ", count = ", bin_count or '[blank]', ", and method = ", bin_method)

    if is_normalize:
        normalize_unit = form_data.get("normalize_unit", type="str")
        lc = lc.normalize(unit=normalize_unit)
        print("``Normalized lightcurve.")

    if is_river_plot:
        river_plot_period    = form_data.get("river_plot_period",     type='float')
        river_plot_time      = form_data.get("river_plot_time",       type='float', default=0.0)
        river_plot_points    = form_data.get("river_plot_points",     type='int',   default=1)
        river_plot_phase_min = form_data.get("river_plot_phase_min",  type='float', default=-0.5)
        river_plot_phase_max = form_data.get("river_plot_phase_max",  type='float', default=0.5)
        river_plot_method    = form_data.get("river_plot_method",     type='str',   default='mean')

        river_plot_axis = lc.plot_river(bin_points=river_plot_points, minimum_phase=river_plot_phase_min, maximum_phase=river_plot_phase_max, method=river_plot_method)
        response.add("river_plot", write_files.river_plot(river_plot_axis))
        print("``Generated river plot.")

    if is_cdpp:
        cdpp_duration   = form_data.get("cdpp_duration",    type='int',   default=13)
        cdpp_window     = form_data.get("cdpp_window",      type='int',   default=101)
        cdpp_polyorder  = form_data.get("cdpp_polyorder",   type='int',   default=2)
        cdpp_sigma      = form_data.get("cdpp_sigma",       type='float', default=5.)

        cdpp_result = lc.estimate_cdpp(transit_duration=cdpp_duration, savgol_window=cdpp_window, savgol_polyorder=cdpp_polyorder, sigma=cdpp_sigma)
        response.add("CDPP Noise Metric", str(cdpp_result) + " ppm", True)
        print("``Computed CDPP Noise Metric.")

    # Next options should really be located in another file todo
    if is_periodogram:
        # Method
        p_method = form_data.get("p_method", type="str", default='lombscargle')
        p_args = {'method': p_method}
        if p_method == 'lombscargle':
            # Get values
            p_args["normalization"]      = form_data.get("p_ls_normalization",      type='str',   default='amplitude')
            p_args["ls_method"]          = form_data.get("p_ls_method",             type='str',   default='fast')
            p_args["oversample_factor"]  = form_data.get("p_ls_oversample",         type='int',   default=None, force=False)
            p_args["nterms"]             = form_data.get("p_ls_nterms",             type='int',   default=1)
            p_args["nyquist_factor"]     = form_data.get("p_ls_nyquist",            type='int',   default=1)
            p_freq_period                = form_data.get("p_ls_freq_period",        type='str',   default=None)
            p_freq_period_unit           = form_data.get("p_ls_frequencies_unit",   type='str',   default='')
            p_freq_period_min            = form_data.get("p_ls_frequencies_min",    type='float', default=None, force=False)
            p_freq_period_max            = form_data.get("p_ls_frequencies_max",    type='float', default=None, force=False)

            # Frequency/Period
            if p_freq_period:
                # Units
                import astropy.units as u
                if p_freq_period_unit == "microhertz":
                    p_args["freq_unit"] = u.microhertz
                elif p_freq_period_unit == "1/day":
                    p_args["freq_unit"] = 1/u.day

                # Values
                if p_freq_period == "frequency":
                    p_args["minimum_frequency"] = p_freq_period_min
                    p_args["maximum_frequency"] = p_freq_period_max
                elif p_freq_period == "period":
                    p_args["minimum_period"]    = p_freq_period_min
                    p_args["maximum_period"]    = p_freq_period_max


        elif p_method == 'boxleastsquares':
            # Get values
            p_args["duration"]          = form_data.get("p_bls_duration",         type='float', default=0.25)
            p_args["frequency_factor"]  = form_data.get("p_bls_frequency_factor", type='int',   default=10)
            p_args["minimum_period"]    = form_data.get("p_bls_minimum_period",   type='float', default=None)
            p_args["maximum_period"]    = form_data.get("p_bls_maximum_period",   type='float', default=None)
            p_period_unit               = form_data.get("p_bls_time_unit",        type='str',   default=None)

            # Units
            import astropy.units as u
            if p_period_unit == "day":
                p_args["time_unit"] = u.day

        # Compute periodogram
        print("``Computing periodogram...")
        periodogram = lc.to_periodogram(**p_args)
        print("``Computed periodogram.")
        response.add("Max Period",             str(periodogram.max_power),              True)
        response.add("Period at Max Power",    str(periodogram.period_at_max_power),    True)
        response.add("Frequency at Max Power", str(periodogram.frequency_at_max_power), True)
        response.add("periodogram_file",       write_files.periodogram(periodogram))


    if is_seismology:
        # diagnose_deltanu, diagnose_numax, estimate_logg(teff), estimate_mass(teff), estimate_radius(teff), plot_echelle
        print("``Seismology not fully implemented yet.")
        lc = lc_seismology if lc_seismology else lc
        seismology = lc.to_seismology()
        response.add("Estimated NuMax",   str(seismology.estimate_numax()),   True, "Frequency of the peak of the seismic oscillation modes envelope")
        response.add("Estimated DeltaNu", str(seismology.estimate_deltanu()), True, "Average value of the large frequency spacing, DeltaNu, of the seismic oscillations of the target")

    response.add("lightcurve_file", write_files.lightcurve(lc, lc_trend))