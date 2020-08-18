from helpers import form_data, response
import lightkurve

def search ():
    # Prepare argument list
    data_archive   = form_data.get("data_archive",   type="str")
    target         = form_data.get("target",         type="str")
    is_search_only = form_data.get("is_search_only", type="boolean")

    # Perform search from archive
    print("``Searching archive for target.")
    if data_archive == 'search_target_pixel':
        args          = prepare_args()
        search_result = lightkurve.search_targetpixelfile(target, **args)
    elif data_archive == 'search_light_curve':
        args          = prepare_args()
        search_result = lightkurve.search_lightcurvefile(target, **args)
    elif data_archive == 'search_tesscut':
        sector = form_data.get("sector", type="list")
        if sector:
            sector        = list(map(int, sector))
            search_result = lightkurve.search_tesscut(target, sector=sector)
        else:
            search_result = lightkurve.search_tesscut(target)

    # Display results
    if is_search_only:
        if data_archive != "search_tesscut":
            response.add("MAST Observation ID", ", ".join(str(v) for v in search_result.obsid), True)
        response.add("search_results", search_result.__repr__(html=True))
        print("``Completed search.")

    return search_result

def prepare_args ():
    # Store data into individual variables
    mission          = form_data.get("mission",       type="list")
    quarter          = form_data.get("quarter",       type="list")
    campaign         = form_data.get("campaign",      type="list")
    sector           = form_data.get("sector",        type="list")
    cadence          = form_data.get("cadence",       type="str")
    month            = form_data.get("month",         type="list")
    search_radius    = form_data.get("search_radius", type="float")
    limit_targets    = form_data.get("limit_targets", type="int")

    # Cadence / radius / bitmask
    args  = dict(cadence=cadence, radius=search_radius)

    # Mission (str, list of str)
    args['mission'] = list(map(str, mission))

    # Kepler Quarter / K2 campaign / TESS sector (int, list of ints)
    if "kepler" in mission:
        if quarter:
            args['quarter'] = list(map(int, quarter))
    if "k2" in mission:
        if campaign:
            args['campaign'] = list(map(int, campaign))
    if "tess" in mission:
        if sector:
            args['sector'] = list(map(int, sector))

    # Month
    if cadence == 'short':
        args['month'] = list(map(int, month))

    # Limit
    if limit_targets.isdigit():
        args['limit'] = int(limit_targets)

    return args