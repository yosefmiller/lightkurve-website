import numpy
#import transit_periodogram.py
#from bls import BLS
from transit_periodogram import transit_periodogram

def modify_lightcurve (form_data, lc):
    is_remove_nans     = form_data.get("is_remove_nans",     False)
    is_remove_outliers = form_data.get("is_remove_outliers", False)
    is_sff_correction  = form_data.get("is_sff_correction",  False)
    is_fill_gaps       = form_data.get("is_fill_gaps",       False)
    is_periodogram     = form_data.get("is_periodogram",     False)
    is_flatten         = form_data.get("is_flatten",         False)
    is_fold            = form_data.get("is_fold",            False)
    is_bin             = form_data.get("is_bin",             False)
    is_normalize       = form_data.get("is_normalize",       False)

    if is_remove_nans:
        lc = lc.remove_nans()
        print("Removed NaNs.")

    if is_remove_outliers:
        sigma = float(form_data.get("sigma") or 5.0)
        lc = lc.remove_outliers(sigma=sigma)
        print("Removed outliers at sigma = ", sigma, ".")

    if is_sff_correction:
        windows = int(form_data.get("windows") or 1)
        lc = lc.to_corrector().correct(windows=windows)
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

        lc = lc.fold(period=float(period), t0=phase)
        print("Folded at period = ", period, " and phase = ", phase, ".")

    if is_bin:
        bin_size   = int(form_data.get("bin_size") or 13)
        bin_method = form_data.get("bin_method") or "mean"
        lc = lc.bin(binsize=bin_size, method=bin_method)
        print("Binned at binsize = ", bin_size, " and method = ", bin_method)

    if is_normalize:
        lc = lc.normalize()
        print("Normalized lightcurve.")

    if is_periodogram:
#        frequencies = float(form_data.get("frequencies") or 1.0)
        print("Computing periodogram.")
        import astropy.units as u
        return lc, lc.to_periodogram(minimum_period=0.9*u.day, maximum_period=1.2*u.day, oversample_factor=10)
    else:
        return lc, False