from helpers import form_data, response
import numpy

def target_pixel_file (flux, pipeline_mask, shape):
    # Write flux to file for plotting
    tpf_file = "outputs/" + form_data.id() + "_tpf.pt"
    pipeline_mask = pipeline_mask.astype(int)
    with open(tpf_file, "w") as f:
        f.write("flux pipeline_mask \n")
        for index in range(shape[1]):
            f.write(','.join([ str(i) for i in flux[index]          ]) + " ")
            f.write(','.join([ str(i) if i == True else str(numpy.nan) for i in pipeline_mask[index] ]) + "\n")
        f.close()

    print("``Wrote TPF flux file.")
    return tpf_file

def lightcurve (lc, lc_trend):
    # Limit the number of values to plot
    limit = 30000
    size = numpy.shape(lc.time)[0]
    rate = int(numpy.round(size/limit)) if size > limit else 1
    indexes = numpy.arange(0, size, rate)

    # Write flux to file for plotting
    lightcurve_file = "outputs/" + form_data.id() + "_lightcurve.pt"
    with open(lightcurve_file, "w") as f:
        f.write("time flux flux_err trend \n")
        for index in indexes:
            f.write(str(numpy.round( lc.time[index],     5 )) + " " +
                    str(numpy.round( lc.flux[index],     5 )) + " " +
                    str(numpy.round( lc.flux_err[index], 8 )) + " " +
                    str(numpy.round( lc_trend.flux[index] or 0, 5 )) + "\n")
        f.close()

    print("``Wrote LC flux file. Limited results to ", limit, " values.")
    return lightcurve_file

def periodogram (p):
    # Limit the number of values to plot
    limit = 5000
    size = numpy.shape(p.frequency)[0]
    rate = int(numpy.round(size/limit)) if size > limit else 1
    indexes = numpy.arange(0, size, rate)

    # Write power to file for plotting
    p_power_file = "outputs/" + form_data.id() + "_periodogram.pt"
    with open(p_power_file, "w") as f:
        f.write("frequencies period power \n")
        for index in indexes:
            f.write(str(numpy.round( p.frequency[index], 3 ).value) + " " +
                    str(numpy.round( p.period[index],    3 ).value) + " " +
                    str(numpy.round( p.power[index],     4 ).value) + "\n")
        f.close()

    print("``Wrote Periodogram power file. Limited results to ", limit, " values.")
    return p_power_file

def river_plot (axis):
    # Get data collection from plot
    coll = axis.collections[0]
    coor = coll._coordinates

    # Extract data from collection
    river = dict()
    river["file"]        = "outputs/" + form_data.id() + "_river.pt"
    river["title"]       = axis.title._text
    river["aspect"]      = axis._aspect
    river["phase"]       = coor[0, :, 0].squeeze().tolist()
    river["cycle"]       = coor[:, 0, 1].squeeze().tolist()
    river["flux_min"]    = coll.colorbar.vmin
    river["flux_max"]    = coll.colorbar.vmax
    river["color_label"] = coll.colorbar._label.replace("\overline{f}", "f̅").replace("\sigma_f", "σf").replace("$","")
    river["color_map"]   = coll.cmap.name.capitalize()
    river_flux_grid      = coll._A.data.reshape( coor.shape[0]-1, coor.shape[1]-1 )

    # Write flux info
    with open(river["file"], "w") as f:
        f.write("flux \n")
        for flux in river_flux_grid:
            f.write(','.join([ str(i.round(7)) for i in flux ]) + "\n")
        f.close()

    return river