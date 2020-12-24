## Lightkurve Web Interface
**A web interface for Kepler & TESS time series analysis implementing the Lightkurve package.**  

### What is Lightkurve?
**Lightkurve** is a community-developed, open-source Python package which offers a beautiful and user-friendly way to analyze astronomical flux time series data, in particular the pixels and lightcurves obtained by **NASA's Kepler and TESS exoplanet missions**.  

The [Lightkurve package](https://github.com/KeplerGO/lightkurve) aims to lower the barrier for students, astronomers, and citizen scientists interested in analyzing Kepler and TESS space telescope data. It does this by providing **high-quality building blocks and tutorials** which enable both hand-tailored data analyses and advanced automated pipelines.

**Documentation:** https://docs.lightkurve.org  

### Why a web interface?
Most scientists and citizen scientists experience coding as a barrier, which prevents them from exploring NASA's TESS, Kepler and K2 mission data.
It is quite common for younger interns not to have installed Python yet. Even more experienced scientists simply don't have the time to install and learn Lightkurve.

A **Lightkurve interface** makes exploring this data so much easier and accessible for everybody.
Calculations are run asynchronously and in tandem on the *NASA Goddard Private Cloud* cloud-computing cluster.
Detailed logs and stack traces are provided to monitor calculation progress and understand errors.
Upon completion, results are saved and made accessible throughout the user's browsing session, enabling the user to compare various results.

### What can it do?
The **Lightkurve Web Interface** can:
- Search the archive for TPFs, TESS cutouts and LCFs
- Choose photometry types (with custom apertures and PRF photometry)
- Select and customize lightcurve processing methods (i.e. fold, flatten, fill time-gaps, remove outliers and NaNs, SFF correction, bin, normalize)
- Convert to periodogram (using the Lomb Scargle or Box Least Squares method), as well as perform basic asteroseismology
- List search results, plot TPFs, light curves, periodograms, river plots, and display output values

For the numerous more-complicated usages of **Lightkurve** not well-suited for the website, a traditional installation would be required.
Nevertheless, in such cases the web interface could still be useful in planning such calculations.

### Contributing
Being that this has been developed entirely by a volunteer undergrad student, contributions and feedback are highly appreciated! Please feel free to [contact me](mailto:yosefmiller613@gmail.com) with any questions, suggestions, bug reports, feature requests, etc.