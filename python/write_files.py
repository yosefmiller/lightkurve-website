import numpy

def tpf_flux (tracking_id, flux, pipeline_mask, shape):
    # Write flux to file for plotting
    tpf_flux_file = "outputs/" + tracking_id + "_tpf_flux.pt"
    pipeline_mask = pipeline_mask.astype(int)
    with open(tpf_flux_file, "w") as f:
        f.write("flux pipeline_mask \n")
        for index in range(shape[1]):
            f.write(','.join([ str(i) for i in flux[index]          ]) + " ")
            f.write(','.join([ str(i) if i == True else str(numpy.nan) for i in pipeline_mask[index] ]) + "\n")
        f.close()

    print("Wrote tpf flux file.")
    return tpf_flux_file

def lc_flux (tracking_id, lc):
    # Limit the number of values to plot
    limit = 10000
    size = numpy.shape(lc.time)[0]
    rate = int(numpy.round(size/limit)) if size > limit else 1
    indexes = numpy.arange(0, size, rate)

    # Write flux to file for plotting
    lc_flux_file = "outputs/" + tracking_id + "_lc_flux.pt"
    with open(lc_flux_file, "w") as f:
        f.write("time flux flux_err \n")
        for index in indexes:
            f.write(str(numpy.round( lc.time[index],     5 )) + " " +
                    str(numpy.round( lc.flux[index],     5 )) + " " +
                    str(numpy.round( lc.flux_err[index], 8 )) + "\n")
        f.close()

    print("Wrote LC flux file. Limited results to ", limit, " values.")
    return lc_flux_file

def p_power (tracking_id, p):
    # Limit the number of values to plot
    limit = 5000
    size = numpy.shape(p.frequency)[0]
    rate = int(numpy.round(size/limit)) if size > limit else 1
    indexes = numpy.arange(0, size, rate)
    #indexes = numpy.arange(0, 10000, 1)

    # Write power to file for plotting
    p_power_file = "outputs/" + tracking_id + "_p_power.pt"
    with open(p_power_file, "w") as f:
        f.write("frequencies period power \n")
        for index in indexes:
            f.write(str(numpy.round( p.frequency[index], 3 ).value) + " " +
                    str(numpy.round( p.period[index],    3 ).value) + " " +
                    str(numpy.round( p.power[index],     4 ).value) + "\n")
            #f.write(str(numpy.round(re.search(r"[-+]?\d*\.\d+|\d+", str(p.frequency[index] ))[0])) + " " +
            #                    str(numpy.round(re.search(r"[-+]?\d*\.\d+|\d+", str(p.power[index]      ))[0])) + "\n")
        f.close()

    print("Wrote Periodogram power file. Limited results to ", limit, " values.")
    return p_power_file