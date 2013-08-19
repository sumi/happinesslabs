/**
 * Title: jqPlot Charts
 * 
 * Pure JavaScript plotting plugin for jQuery.
 * 
 * About: Version
 * 
 * 0.6.6b 
 * 
 * About: Copyright & License
 * 
 * Copyright (c) 2009 Chris Leonello.  This software is licensed under the GPL version 2.0 and MIT licenses.
 * See <GPL Version 2> and <MIT License> contained within this
 * distribution for further information.
 * 
 * About: Introduction
 * 
 * jqPlot requires jQuery (tested with 1.3.2 or better). jQuery 1.3.2 is included in the distribution.  
 * To use jqPlot include jQuery, the jqPlot jQuery plugin, and optionally the excanvas script for IE support in your web page:
 * 
 * > <!--[if IE]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
 * > <script language="javascript" type="text/javascript" src="jquery-1.3.2.min.js"></script>
 * > <script language="javascript" type="text/javascript" src="jquery.jqplot.min.js"></script>
 * 
 * jqPlot can be customized by overriding the defaults of any of the objects which make
 * up the plot.  The general usage of jqplot is:
 * 
 * > chart = $.jqplot('targetElemId', [dataArray,...], {optionsObject});
 * 
 * Which would look like this 
 * in actual code:
 * 
 * > $.jqplot('chartdiv',  [[[1, 2],[3,5.12],[5,13.1],[7,33.6],[9,85.9],[11,219.9]]]);
 * 
 * The optionsObject corresponds to an properties on a <jqPlot> instance, so customization
 * may look like this:
 * 
 * > chart = $.jqplot('targetElemId', [dataArray, ...], {title:'My Plot', axes:{xaxis:{min:0, max:10}}});
 * 
 * For more inforrmation, see <jqPlot Usage>.
 * 
 * About: Usage
 * 
 * See <jqPlot Usage>
 * 
**/

(function($) {
    // make sure undefined is undefined
    var undefined;

    /**
     * Class: $.jqplot
     * jQuery function called by the user to create a plot.
     *  
     * Parameters:
     * target - ID of target element to render the plot into.
     * data - an array of data series.
     * options - user defined options object.  See the individual classes for available options.
     */

    $.jqplot = function(target, data, options) {
        var _data, _options;
        
        // check to see if only 2 arguments were specified, what is what.
        if (data == null) {
            throw "No data specified";
        }
        if (data.constructor == Array && data.length == 0 || data[0].constructor != Array) {
            throw "Improper Data Array";
        }
        if (options == null) {
            if (data instanceof Array) {
                _data = data;
                _options = null;   
            }
            
            else if (data.constructor == Object) {
                _data = null;
                _options = data;
            }
        }
        else {
            _data = data;
            _options = options;
        }
        var plot = new jqPlot();
        plot.init(target, _data, _options);
        plot.draw();
        return plot;
    };
    
    // declare some commonly used iteration variables.
    
    $.jqplot.debug = 1;
    
    // path to jqplot install, relative to the script that is including jqplot.
    $.jqplot.installPath = 'jqplot';
    $.jqplot.pluginsPath = 'jqplot/plugins';
    
    $.jqplot.preInitHooks = [];
    $.jqplot.postInitHooks = [];
    $.jqplot.preParseOptionsHooks = [];
    $.jqplot.postParseOptionsHooks = [];
    $.jqplot.preDrawHooks = [];
    $.jqplot.postDrawHooks = [];
    $.jqplot.preDrawSeriesHooks = [];
    $.jqplot.postDrawSeriesHooks = [];
    $.jqplot.preDrawLegendHooks = [];
    $.jqplot.addLegendRowHooks = [];
    $.jqplot.preSeriesInitHooks = [];
    $.jqplot.postSeriesInitHooks = [];
    $.jqplot.preParseSeriesOptionsHooks = [];
    $.jqplot.postParseSeriesOptionsHooks = [];
    $.jqplot.eventListenerHooks = [];

    // A superclass holding some common properties and methods.
    $.jqplot.ElemContainer = function() {
        this._elem;
        this._plotWidth;
        this._plotHeight;
        this._plotDimensions = {height:null, width:null};
    };
    
    $.jqplot.ElemContainer.prototype.getWidth = function() {
        return this._elem.outerWidth(true);
    };
    
    $.jqplot.ElemContainer.prototype.getHeight = function() {
        return this._elem.outerHeight(true);
    };
    
    $.jqplot.ElemContainer.prototype.getPosition = function() {
        return this._elem.position();
    };
    
    $.jqplot.ElemContainer.prototype.getTop = function() {
        return this.getPosition().top;
    };
    
    $.jqplot.ElemContainer.prototype.getLeft = function() {
        return this.getPosition().left;
    };
    
    $.jqplot.ElemContainer.prototype.getBottom = function() {
        return this._elem.css('bottom');
    };
    
    $.jqplot.ElemContainer.prototype.getRight = function() {
        return this._elem.css('right');
    };
    

    /**
     * Class: Axis
     * An individual axis object.  Cannot be instantiated directly, but created
     * by the Plot oject.  Axis properties can be set or overriden by the 
     * options passed in from the user.
     * 
     * Parameters:
     * name - Axis name (identifier).  One of 'xaxis', 'yaxis', 'x2axis' or 'y2axis'.
     */
    function Axis(name) {
        $.jqplot.ElemContainer.call(this);
        // Group: Properties
        
        // prop: name
        // Axis name (identifier).  One of 'xaxis', 'yaxis', 'x2axis' or 'y2axis'.
        this.name = name;
        this._series = [];
        // prop: show
        // Wether to display the axis on the graph.
        this.show = false;
        // prop: min
        // minimum value of the axis (in data units, not pixels).
        this.min=null;
        // prop: max
        // maximum value of the axis (in data units, not pixels).
        this.max=null;
        // prop: pad
        // Padding to extend the range above and below the data bounds.
        // Factor to multiply by the data range when setting the axis bounds
        this.pad = 1.2;
        // prop: ticks
        // 1D [val, val, ...] or 2D [[val, label], [val, label], ...] array of ticks for the axis.
        // If no label is specified, the value is formatted into an appropriate label.
        this.ticks = [];
        // prop: numberTicks
        // Desired number of ticks.  Default is to compute automatically.
        this.numberTicks;
        // prop: tickInterval
        // number of units between ticks.  Mutually exclusive with numberTicks.
        this.tickInterval;
        // prop: renderer
        // A class of a rendering engine that handles tick generation, 
        // scaling input data to pixel grid units and drawing the axis element.
        this.renderer = $.jqplot.LinearAxisRenderer;
        // prop: rendererOptions
        // renderer specific options.  Not commonly used.
        this.rendererOptions = {};
        // prop: tickOptions
        // Options that will be passed to the tickRenderer, see <$.jqplot.AxisTickRenderer> options.
        this.tickOptions = {};
        // prop: showTicks
        // wether to show the ticks (both marks and labels) or not.
        this.showTicks = true;
        // prop: showTickMarks
        // wether to show the tick marks (line crossing grid) or not.
        this.showTickMarks = true;
        // prop: showMinorTicks
        // Wether or not to show minor ticks.  This is renderer dependent.
        // The default <$.jqplot.LinearAxisRenderer> does not have minor ticks.
        this.showMinorTicks = true;
        // minimum and maximum values on the axis.
        this._dataBounds = {min:null, max:null};
        // pixel position from the top left of the min value and max value on the axis.
        this._offsets = {min:null, max:null};

        this._plotWidth;
        this._plotHeight;
        this._ticks=[];
    }
    
    Axis.prototype = new $.jqplot.ElemContainer();
    Axis.prototype.constructor = Axis;
    
    Axis.prototype.init = function() {
        this.renderer = new this.renderer();
        this.tickOptions.axis = this.name;
        this.renderer.init.call(this, this.rendererOptions);
        
    };
    
    Axis.prototype.draw = function(ctx) {
        return this.renderer.draw.call(this, ctx);
    };
    
    Axis.prototype.set = function() {
        this.renderer.set.call(this);
    };
    
    Axis.prototype.pack = function(pos, offsets) {
        if (this.show) {
            this.renderer.pack.call(this, pos, offsets);
        }
    };

    /**
     * Class: Legend
     * Legend object.  Cannot be instantiated directly, but created
     * by the Plot oject.  Legend properties can be set or overriden by the 
     * options passed in from the user.
     */
    function Legend() {
        $.jqplot.ElemContainer.call(this);
        // Group: Properties
        
        // prop: show
        // Wether to display the legend on the graph.
        this.show = false;
        // prop: location
        // Placement of the legend.  one of the compass directions: nw, n, ne, e, se, s, sw, w
        this.location = 'ne';
        // prop: xoffset
        // offset from the inside edge of the plot in the x direction in pixels.
        this.xoffset = 12;
        // prop: yoffset
        // offset from the inside edge of the plot in the y direction in pixels.
        this.yoffset = 12;
        // prop: border
        // css spec for the border around the legend box.
        this.border;
        // prop: background
        // css spec for the background of the legend box.
        this.background;
        // prop: textColor
        // css color spec for the legend text.
        this.textColor;
        // prop: fontFamily
        // css font-family spec for the legend text.
        this.fontFamily; 
        // prop: fontSize
        // css font-size spec for the legend text.
        this.fontSize ;
        // prop: rowSpacing
        // css padding-top spec for the rows in the legend.
        this.rowSpacing = '0.5em';
        // renderer
        // A class that will create a DOM object for the legend,
        // see <$.jqplot.TableLegendRenderer>.
        this.renderer = $.jqplot.TableLegendRenderer;
        // prop: rendererOptions
        // renderer specific options passed to the renderer.
        this.rendererOptions = {};
        this._series = [];
    }
    
    Legend.prototype = new $.jqplot.ElemContainer();
    Legend.prototype.constructor = Legend;
    
    Legend.prototype.init = function() {
        this.renderer = new this.renderer();
        this.renderer.init.call(this, this.rendererOptions);
    };
    
    Legend.prototype.draw = function(offsets) {
        for (var i=0; i<$.jqplot.preDrawLegendHooks.length; i++){
            $.jqplot.preDrawLegendHooks[i].call(this, offsets);
        }
        return this.renderer.draw.call(this, offsets);
    };
    
    Legend.prototype.pack = function(offsets) {
        this.renderer.pack.call(this, offsets);
    };
    
    $.jqplot.TableLegendRenderer = function(){
        //
    };

    /**
     * Class: Title
     * Plot Title object.  Cannot be instantiated directly, but created
     * by the Plot oject.  Title properties can be set or overriden by the 
     * options passed in from the user.
     * 
     * Parameters:
     * text - text of the title.
     */
    function Title(text) {
        $.jqplot.ElemContainer.call(this);
        // Group: Properties
        
        // prop: text
        // text of the title;
        this.text = text;
        // prop: show
        // wether or not to show the title
        this.show = true;
        // prop: fontFamily
        // css font-family spec for the text.
        this.fontFamily;
        // prop: fontSize
        // css font-size spec for the text.
        this.fontSize ;
        // prop: textAlign
        // css text-align spec for the text.
        this.textAlign;
        // prop: textColor
        // css color spec for the text.
        this.textColor;
        // prop: renderer
        // A class for creating a DOM element for the title,
        // see <$.jqplot.DivTitleRenderer>.
        this.renderer = $.jqplot.DivTitleRenderer;
        // prop: rendererOptions
        // renderer specific options passed to the renderer.
        this.rendererOptions = {};   
    }
    
    Title.prototype = new $.jqplot.ElemContainer();
    Title.prototype.constructor = Title;
    
    Title.prototype.init = function() {
        this.renderer = new this.renderer();
        this.renderer.init.call(this, this.rendererOptions);
    };
    
    Title.prototype.draw = function(width) {
        return this.renderer.draw.call(this, width);
    };
    
    Title.prototype.pack = function() {
        this.renderer.pack.call(this);
    };


    /**
     * Class: Series
     * An individual data series object.  Cannot be instantiated directly, but created
     * by the Plot oject.  Series properties can be set or overriden by the 
     * options passed in from the user.
     */
    function Series() {
        $.jqplot.ElemContainer.call(this);
        // Group: Properties
        
        // prop: show
        // wether or not to draw the series.
        this.show = true;
        // prop: xaxis
        // name of x axis to associate with this series, either 'xaxis' or 'x2axis'.
        this.xaxis = 'xaxis';
        this._xaxis;
        // prop: yaxis
        // name of y axis to associate with this series, either 'yaxis' or 'y2axis'.
        this.yaxis = 'yaxis';
        this._yaxis;
        this.gridBorderWidth = 2.0;
        // prop: renderer
        // A class of a renderer which will draw the series, 
        // see <$.jqplot.LineRenderer>.
        this.renderer = $.jqplot.LineRenderer;
        // prop: rendererOptions
        // Options to pass on to the renderer.
        this.rendererOptions = {};
        // prop: data
        // data points for the line in series units.
        // converted to an array of (x,y) data points with x starting at 1 if a 1D array of y values is passed in.
        this.data = [];
        // prop: gridData
        // data points for the line in grid pixel units.
        this.gridData = [];
        // prop: label
        // Line label to use in the legend.
        this.label = '';
        // prop: color
        // css color spec for the series
        this.color;
        // prop: lineWidth
        // width of the line in pixels.  May have different meanings depending on renderer.
        this.lineWidth = 2.5;
        // prop: shadow
        // wether or not to draw a shadow on the line
        this.shadow = true;
        // prop: shadowAngle
        // Shadow angle in degrees
        this.shadowAngle = 45;
        // prop: shadowOffset
        // Shadow offset from line in pixels
        this.shadowOffset = 1.5;
        // prop: shadowDepth
        // Number of times shadow is stroked, each stroke offset shadowOffset from the last.
        this.shadowDepth = 3;
        // prop: shadowAlpha
        // Alpha channel transparency of shadow.  0 = transparent.
        this.shadowAlpha = '0.07';
        // prop: breakOnNull
        // wether line segments should be be broken at null value.
        // False will join point on either side of line.
        this.breakOnNull = false;
        // prop: markerRenderer
        // A class of a renderer which will draw marker (e.g. circle, square, ...) at the data points,
        // see <$.jqplot.MarkerRenderer>.
        this.markerRenderer = $.jqplot.MarkerRenderer;
        // prop: markerOptions
        // renderer specific options to pass to the markerRenderer,
        // see <$.jqplot.MarkerRenderer>.
        this.markerOptions = {};
        // prop: showLine
        // wether to actually draw the line or not.  Series will still be renderered, even if no line is drawn.
        this.showLine = true;
        // prop: showMarker
        // wether or not to show the markers at the data points.
        this.showMarker = true;
        // prop: index
        // 0 based index of this series in the plot series array.
        this.index;
        // prop: fill
        // true or false, wether to fill under lines or in bars.
        // May not be implemented in all renderers.
        this.fill = false;
        // _stack is set by the Plot if the plot is a stacked chart.
        // will stack lines or bars on top of one another to build a "mountain" style chart.
        // May not be implemented in all renderers.
        this._stack = false;
        this._stackData = [];
        this._plotData = [];
        // data from the previous series, for stacked charts.
        this._prevPlotData = [];
        this._prevGridData = [];
        this._stackAxis = 'y';
        this.plugins = {};
    }
    
    Series.prototype = new $.jqplot.ElemContainer();
    Series.prototype.constructor = Series;
    
    Series.prototype.init = function(index, gridbw) {
        // weed out any null values in the data.
        this.index = index;
        this.gridBorderWidth = gridbw;
        var d = this.data;
        for (var i=0; i<d.length; i++) {
            if (! this.breakOnNull) {
                if (d[i] == null || d[i][0] == null || d[i][1] == null) {
                    d.splice(i,1);
                    continue;
                }
            }
            else {
                if (d[i] == null || d[i][0] == null || d[i][1] == null) {
                    // figure out what to do with null values
                }
            }
        }
        this.renderer = new this.renderer();
        this.renderer.init.call(this, this.rendererOptions);
        this.markerRenderer = new this.markerRenderer();
        if (!this.markerOptions.color) {
            this.markerOptions.color = this.color;
        }
        if (this.markerOptions.show == null) {
            this.markerOptions.show = this.showMarker;
        }
        // the markerRenderer is called within it's own scaope, don't want to overwrite series options!!
        this.markerRenderer.init(this.markerOptions);
    };
    
    // data - optional data point array to draw using this series renderer
    // gridData - optional grid data point array to draw using this series renderer
    // stackData - array of cumulative data for stacked plots.
    Series.prototype.draw = function(sctx, opts) {
        var options = (opts == undefined) ? {} : opts;
        // hooks get called even if series not shown
        // we don't clear canvas here, it would wipe out all other series as well.
        for (var j=0; j<$.jqplot.preDrawSeriesHooks.length; j++) {
            $.jqplot.preDrawSeriesHooks[j].call(this, sctx, options);
        }
        if (this.show) {
            this.renderer.setGridData.call(this);
            if (!options.preventJqPlotSeriesDrawTrigger) {
                $(sctx.canvas).trigger('jqplotSeriesDraw', [this.data, this.gridData]);
            }
            var data = [];
            if (options.data) {
                data = options.data;
            }
            else if (!this._stack) {
                data = this.data;
            }
            else {
                data = this._plotData;
            }
            var gridData = options.gridData || this.renderer.makeGridData.call(this, data);
        
            this.renderer.draw.call(this, sctx, gridData, options);
        }
        
        for (var j=0; j<$.jqplot.postDrawSeriesHooks.length; j++) {
            $.jqplot.postDrawSeriesHooks[j].call(this, sctx, options);
        }
    };
    


    /**
     * Class: Grid
     * 
     * Object representing the grid on which the plot is drawn.  The grid in this
     * context is the area bounded by the axes, the area which will contain the series.
     * Note, the series are drawn on their own canvas.
     * The Grid object cannot be instantiated directly, but is created by the Plot oject.  
     * Grid properties can be set or overriden by the options passed in from the user.
     */
    function Grid() {
        $.jqplot.ElemContainer.call(this);
        // Group: Properties
        
        // prop: drawGridlines
        // wether to draw the gridlines on the plot.
        this.drawGridlines = true;
        // prop: background
        // css spec for the background color.
        this.background = '#fffdf6';
        // prop: borderColor
        // css spec for the color of the grid border.
        this.borderColor = '#999999';
        // prop: borderWidth
        // width of the border in pixels.
        this.borderWidth = 2.0;
        // prop: shadow
        // wether to show a shadow behind the grid.
        this.shadow = true;
        // prop: shadowAngle
        // shadow angle in degrees
        this.shadowAngle = 45;
        // prop: shadowOffset
        // Offset of each shadow stroke from the border in pixels
        this.shadowOffset = 1.5;
        // prop: shadowWidth
        // width of the stoke for the shadow
        this.shadowWidth = 3;
        // prop: shadowDepth
        // Number of times shadow is stroked, each stroke offset shadowOffset from the last.
        this.shadowDepth = 3;
        // prop: shadowAlpha
        // Alpha channel transparency of shadow.  0 = transparent.
        this.shadowAlpha = '0.07';
        this._left;
        this._top;
        this._right;
        this._bottom;
        this._width;
        this._height;
        this._axes = [];
        // prop: renderer
        // Instance of a renderer which will actually render the grid,
        // see <$.jqplot.CanvasGridRenderer>.
        this.renderer = $.jqplot.CanvasGridRenderer;
        // prop: rendererOptions
        // Options to pass on to the renderer,
        // see <$.jqplot.CanvasGridRenderer>.
        this.rendererOptions = {};
        this._offsets = {top:null, bottom:null, left:null, right:null};
    }
    
    Grid.prototype = new $.jqplot.ElemContainer();
    Grid.prototype.constructor = Grid;
    
    Grid.prototype.init = function() {
        this.renderer = new this.renderer();
        this.renderer.init.call(this, this.rendererOptions);
    };
    
    Grid.prototype.createElement = function(offsets) {
        this._offsets = offsets;
        return this.renderer.createElement.call(this);
    };
    
    Grid.prototype.draw = function() {
        this.renderer.draw.call(this);
    };
    
    $.jqplot.GenericCanvas = function() {
        $.jqplot.ElemContainer.call(this);
        this._ctx;  
    };
    
    $.jqplot.GenericCanvas.prototype = new $.jqplot.ElemContainer();
    $.jqplot.GenericCanvas.prototype.constructor = $.jqplot.GenericCanvas;
    
    $.jqplot.GenericCanvas.prototype.createElement = function(offsets, clss, plotDimensions) {
        this._offsets = offsets;
        var klass = 'jqplot';
        if (clss != undefined) {
            klass = clss;
        }
        var elem = document.createElement('canvas');
        // if new plotDimensions supplied, use them.
        if (plotDimensions != undefined) {
            this._plotDimensions = plotDimensions;
        }
        elem.width = this._plotDimensions.width - this._offsets.left - this._offsets.right;
        elem.height = this._plotDimensions.height - this._offsets.top - this._offsets.bottom;
        this._elem = $(elem);
        this._elem.addClass(klass);
        this._elem.css({ position: 'absolute', left: this._offsets.left, top: this._offsets.top });
        // borrowed from flot by Ole Laursen
        if ($.browser.msie) {
            window.G_vmlCanvasManager.init_(document);
        }
        if ($.browser.msie) {
            elem = window.G_vmlCanvasManager.initElement(elem);
        }
        return this._elem;
    };
    
    $.jqplot.GenericCanvas.prototype.setContext = function() {
        this._ctx = this._elem.get(0).getContext("2d");
        return this._ctx;
    };

    /**
     * Class: jqPlot
     * Plot object returned to call to $.jqplot.  Handles parsing user options,
     * creating sub objects (Axes, legend, title, series) and rendering the plot.
     */
    function jqPlot() {
        // Group: Properties
        
        // prop: data
        // user's data.  Should be in the form of
        // [ [[x1, y1], [x2, y2],...], [[x1, y1], [x2, y2], ...] ] or can be supplied in the series option like:
        // [{ data:[[x1, y1], [x2, y2],...], other_options...}, { data:[[x1, y1], [x2, y2],...], other_options...} ]
        this.data = [];
        // The id of the dom element to render the plot into
        this.targetId = null;
        // the jquery object for the dom target.
        this.target = null; 
        // prop: defaults
        // Default options.  Any of these can be specified individually and be applied
        // to all objects of the type.  A quick way to override all axes or series options.   
        // 
        // Properties
        //
        // axesDefaults - defaults applied to all axes.
        // axes - axis by axis defaults.  Include all 4 axes, even if they are empty.
        // seriesDefaults - deraults applied to all series.
        // gridPadding - default padding around the grid drawing area if no axis or title
        //    is present on a given side of the grid.
        // series - series by series defaults.  Don't know how useful this is.
        this.defaults = {
            axesDefaults: {},
            axes: {xaxis:{}, yaxis:{}, x2axis:{}, y2axis:{}},
            seriesDefaults: {},
            gridPadding: {top:10, right:10, bottom:10, left:10},
            series:[]
        };
        // prop: series
        // Array of series object options.
        this.series = [];
        // prop: axes
        // up to 4 axes are supported, each with it's own options, 
        // See <Axis> for axis specific options.
        this.axes = {xaxis: new Axis('xaxis'), yaxis: new Axis('yaxis'), x2axis: new Axis('x2axis'), y2axis: new Axis('y2axis')};
        // prop: grid
        // See <Grid> for grid specific options.
        this.grid = new Grid();
        // prop: legend
        // see <$.jqplot.TableLegendRenderer>
        this.legend = new Legend();
        this.baseCanvas = new $.jqplot.GenericCanvas();
        this.seriesCanvas = new $.jqplot.GenericCanvas();
        this.eventCanvas = new $.jqplot.GenericCanvas();
        this._width = null;
        this._height = null; 
        this._plotDimensions = {height:null, width:null};
        this._gridPadding = {top:10, right:10, bottom:10, left:10};
        // prop: equalXTicks
        // for dual axes, wether to space ticks the same on both sides.
        this.equalXTicks = true;
        // prop: equalYTicks
        // for dual axes, wether to space ticks the same on both sides.
        this.equalYTicks = true;
        // prop: seriesColors
        // default colors for the series.#c29549
        this.seriesColors = [ "#4bb2c5", "#c5b47f", "#EAA228", "#579575", "#839557", "#958c12", "#953579", "#4b5de4", "#d8b83f", "#ff5800", "#0085cc"];
        var seriesColorsIndex = 0;
        // prop textColor
        // css spec for the css color attribute.  Default for the entire plot.
        this.textColor;
        // prop; fontFamily
        // css spec for the font-family attribute.  Default for the entire plot.
        this.fontFamily;
        // prop: fontSize
        // css spec for the font-size attribute.  Default for the entire plot.
        this.fontSize;
        // prop: title
        this.title = new Title();
        // container to hold all of the merged options.  Convienence for plugins.
        this.options = {};
        // prop: stackSeries
        // true or false, creates a stack or "mountain" plot.
        // Not all series renderers may implement this option.
        this.stackSeries = false;
        // array to hold the cumulative stacked series data.
        // used to ajust the individual series data, which won't have access to other
        // series data.
        this._stackData = [];
        // array that holds the data to be plotted. This will be the series data
        // merged with the the appropriate data from _stackData according to the stackAxis.
        this._plotData = [];
        // Namespece to hold plugins.  Generally non-renderer plugins add themselves to here.
        this.plugins = {};
            
        this.init = function(target, data, options) {
            for (var i=0; i<$.jqplot.preInitHooks.length; i++) {
                $.jqplot.preInitHooks[i].call(this, target, data, options);
            }
            this.targetId = target;
            this.target = $('#'+target);
            if (!this.target.get(0)) {
                throw "No plot target specified";
            }
            
            // make sure the target is positioned by some means and set css
            if (this.target.css('position') == 'static') {
                this.target.css('position', 'relative');
            }
            if (!this.target.hasClass('jqplot-target')) {
                this.target.addClass('jqplot-target');
            }
            
            // if no height or width specified, use a default.
            if (!this.target.height()) {
                this._height = 300;
                this.target.css('height', '300px');
            }
            else {
                this._height = this.target.height();
            }
            if (!this.target.width()) {
                this._width = 400;
                this.target.css('width', '400px');
            }
            else {
                this._width = this.target.width();
            }
            
            this._plotDimensions.height = this._height;
            this._plotDimensions.width = this._width;
            this.grid._plotDimensions = this._plotDimensions;
            this.title._plotDimensions = this._plotDimensions;
            this.baseCanvas._plotDimensions = this._plotDimensions;
            this.seriesCanvas._plotDimensions = this._plotDimensions;
            this.eventCanvas._plotDimensions = this._plotDimensions;
            this.legend._plotDimensions = this._plotDimensions;
            if (this._height <=0 || this._width <=0 || !this._height || !this._width) {
                throw "Canvas dimensions <=0";
            }
            
            this.data = data;
            
            this.parseOptions(options);
            
            if (this.textColor) {
                this.target.css('color', this.textColor);
            }
            if (this.fontFamily) {
                this.target.css('font-family', this.fontFamily);
            }
            if (this.fontSize) {
                this.target.css('font-size', this.fontSize);
            }
            
            this.title.init();
            this.legend.init();
            for (var i=0; i<this.series.length; i++) {
                for (var j=0; j<$.jqplot.preSeriesInitHooks.length; j++) {
                    $.jqplot.preSeriesInitHooks[j].call(this.series[i], target, data, options);
                }
                this.populatePlotData(this.series[i], i);
                this.series[i]._plotDimensions = this._plotDimensions;
                this.series[i].init(i, this.grid.borderWidth);
                for (var j=0; j<$.jqplot.postSeriesInitHooks.length; j++) {
                    $.jqplot.postSeriesInitHooks[j].call(this.series[i], target, data, options);
                }
            }

            for (var name in this.axes) {
                this.axes[name]._plotDimensions = this._plotDimensions;
                this.axes[name].init();
            }
            
            this.grid.init();
            this.grid._axes = this.axes;
            
            this.legend._series = this.series;
            
            for (var i=0; i<$.jqplot.postInitHooks.length; i++) {
                $.jqplot.postInitHooks[i].call(this, target, data, options);
            }
        };  
        
        // populate the _stackData and _plotData arrays for the plot and the series.
        this.populatePlotData = function(series, index) {
            /////////////////
            ///// shouldn't loop through all the series.  it's already being called for each series.
            //for (var i=0; i<this.series.length; i++) {
                // if a stacked chart, compute the stacked data
                if (this.stackSeries) {
                    series._stack = true;
                    var sidx = series._stackAxis == 'x' ? 0 : 1;
                    var idx = sidx ? 0 : 1;
                    // push the current data into stackData
                    //this._stackData.push(this.series[i].data);
                    var temp = $.extend(true, [], series.data);
                    // create the data that will be plotted for this series
                    var plotdata = $.extend(true, [], series.data);
                    // for first series, nothing to add to stackData.
                    for (var j=0; j<index; j++) {
                        var cd = this.series[j].data;
                        for (var k=0; k<cd.length; k++) {
                            temp[k][0] += cd[k][0];
                            temp[k][1] += cd[k][1];
                            // only need to sum up the stack axis column of data
                            plotdata[k][sidx] += cd[k][sidx];
                        }
                    }
                    this._plotData.push(plotdata);
                    this._stackData.push(temp);
                    series._stackData = temp;
                    series._plotData = plotdata;
                }
                else {
                    this._stackData.push(series.data);
                    this.series[index]._stackData = series.data;
                    this._plotData.push(series.data);
                    series._plotData = series.data;
                }
                if (index>0) {
                    series._prevPlotData = this.series[index-1]._plotData;
                }
            //}
        };
        
        this.getNextSeriesColor = function() {
            var c = this.seriesColors[seriesColorsIndex];
            seriesColorsIndex++;
            return c;
        };
    
        this.parseOptions = function(options){
            for (var i=0; i<$.jqplot.preParseOptionsHooks.length; i++) {
                $.jqplot.preParseOptionsHooks[i].call(this, options);
            }
            this.options = $.extend(true, {}, this.defaults, options);
            this.stackSeries = this.options.stackSeries;
            this._gridPadding = this.options.gridPadding;
            for (var n in this.axes) {
                var axis = this.axes[n];
                $.extend(true, axis, this.options.axesDefaults, this.options.axes[n]);
                axis._plotWidth = this._width;
                axis._plotHeight = this._height;
            }
            if (this.data.length == 0) {
                this.data = [];
                for (var i=0; i<this.options.series.length; i++) {
                    this.data.push(this.options.series.data);
                }    
            }
                
            var normalizeData = function(data) {
                // return data as an array of point arrays,
                // in form [[x1,y1...], [x2,y2...], ...]
                var temp = [];
                var i;
                if (!(data[0] instanceof Array)) {
                    // we have a series of scalars.  One line with just y values.
                    // turn the scalar list of data into a data array of form:
                    // [[1, data[0]], [2, data[1]], ...]
                    for (var i=0; i<data.length; i++) {
                        temp.push([i+1, data[i]]);
                    }
                }
            
                else {
                    // we have a properly formatted data series, copy it.
                    $.extend(true, temp, data);
                }
                return temp;
            };

            for (var i=0; i<this.data.length; i++) { ;
                var temp = new Series();
                for (var j=0; j<$.jqplot.preParseSeriesOptionsHooks.length; j++) {
                    $.jqplot.preParseSeriesOptionsHooks[j].call(temp, this.options.seriesDefaults, this.options.series[i]);
                }
                $.extend(true, temp, this.options.seriesDefaults, this.options.series[i]);
                temp.data = normalizeData(this.data[i]);
                switch (temp.xaxis) {
                    case 'xaxis':
                        temp._xaxis = this.axes.xaxis;
                        break;
                    case 'x2axis':
                        temp._xaxis = this.axes.x2axis;
                        break;
                    default:
                        break;
                }
                switch (temp.yaxis) {
                    case 'yaxis':
                        temp._yaxis = this.axes.yaxis;
                        break;
                    case 'y2axis':
                        temp._yaxis = this.axes.y2axis;
                        break;
                    default:
                        break;
                }
                temp._xaxis._series.push(temp);
                temp._yaxis._series.push(temp);
                if (temp.show) {
                    temp._xaxis.show = true;
                    temp._yaxis.show = true;
                }

                // parse the renderer options and apply default colors if not provided
                if (!temp.color && temp.show != false) {
                    temp.color = this.getNextSeriesColor();
                }
                if (!temp.label) {
                    temp.label = 'Series '+ (i+1).toString();
                }
                // temp.rendererOptions.show = temp.show;
                // $.extend(true, temp.renderer, {color:this.seriesColors[i]}, this.rendererOptions);
                this.series.push(temp);  
                for (var j=0; j<$.jqplot.postParseSeriesOptionsHooks.length; j++) {
                    $.jqplot.postParseSeriesOptionsHooks[j].call(this.series[i], this.options.seriesDefaults, this.options.series[i]);
                }
            }
            
            // copy the grid and title options into this object.
            $.extend(true, this.grid, this.options.grid);
            if (typeof this.options.title == 'string') {
                this.title.text = this.options.title;
            }
            else if (typeof this.options.title == 'object') {
                $.extend(true, this.title, this.options.title);
            }
            this.title._plotWidth = this._width;
            $.extend(true, this.legend, this.options.legend);
            
            for (var i=0; i<$.jqplot.postParseOptionsHooks.length; i++) {
                $.jqplot.postParseOptionsHooks[i].call(this, options);
            }
        };
    
        this.draw = function(){
            for (var i=0; i<$.jqplot.preDrawHooks.length; i++) {
                $.jqplot.preDrawHooks[i].call(this);
            }
            // create an underlying canvas to be used for special features.
            this.target.append(this.baseCanvas.createElement({left:0, right:0, top:0, bottom:0}, 'jqplot-base-canvas'));
            var bctx = this.baseCanvas.setContext();
            this.target.append(this.title.draw());
            this.title.pack({top:0, left:0});
            for (var name in this.axes) {
                this.target.append(this.axes[name].draw(bctx));
                this.axes[name].set();
            }
            if (this.axes.yaxis.show) {
                this._gridPadding.left = this.axes.yaxis.getWidth();
            }
            if (this.axes.y2axis.show) {
                this._gridPadding.right = this.axes.y2axis.getWidth();
            }
            if (this.title.show && this.axes.x2axis.show) {
                this._gridPadding.top = this.title.getHeight() + this.axes.x2axis.getHeight();
            }
            else if (this.title.show) {
                this._gridPadding.top = this.title.getHeight();
            }
            else if (this.axes.x2axis.show) {
                this._gridPadding.top = this.axes.x2axis.getHeight();
            }
            if (this.axes.xaxis.show) {
                this._gridPadding.bottom = this.axes.xaxis.getHeight();
            }
            
            this.axes.xaxis.pack({position:'absolute', bottom:0, left:0, width:this._width}, {min:this._gridPadding.left, max:this._width - this._gridPadding.right});
            this.axes.yaxis.pack({position:'absolute', top:0, left:0, height:this._height}, {min:this._height - this._gridPadding.bottom, max: this._gridPadding.top});
            this.axes.x2axis.pack({position:'absolute', top:this.title.getHeight(), left:0, width:this._width}, {min:this._gridPadding.left, max:this._width - this._gridPadding.right});
            this.axes.y2axis.pack({position:'absolute', top:0, right:0}, {min:this._height - this._gridPadding.bottom, max: this._gridPadding.top});
            
            this.target.append(this.grid.createElement(this._gridPadding));
            this.grid.draw();
            this.target.append(this.seriesCanvas.createElement(this._gridPadding, 'jqplot-series-canvas'));
            var sctx = this.seriesCanvas.setContext();
            this.target.append(this.eventCanvas.createElement(this._gridPadding, 'jqplot-event-canvas'));
            var ectx = this.eventCanvas.setContext();
            ectx.fillStyle = 'rgba(0,0,0,0)';
            ectx.fillRect(0,0,ectx.canvas.width, ectx.canvas.height);
            
            // bind custom event handlers to regular events.
            this.bindCustomEvents();
            
            this.drawSeries(sctx);

            // finally, draw and pack the legend
            this.target.append(this.legend.draw());
            this.legend.pack(this._gridPadding);
            
            // register event listeners on the overlay canvas
            for (var i=0; i<$.jqplot.eventListenerHooks.length; i++) {
                var h = $.jqplot.eventListenerHooks[i];
                // in the handler, this will refer to the eventCanvas dom element.
                // make sure there are references back into plot objects.
                this.eventCanvas._elem.bind(h[0], {plot:this}, h[1]);
            }

            for (var i=0; i<$.jqplot.postDrawHooks.length; i++) {
                $.jqplot.postDrawHooks[i].call(this);
            }
        };
        
        this.bindCustomEvents = function() {
            this.eventCanvas._elem.bind('click', {plot:this}, this.onClick);
            this.eventCanvas._elem.bind('mousedown', {plot:this}, this.onMouseDown);
            this.eventCanvas._elem.bind('mouseup', {plot:this}, this.onMouseUp);
            this.eventCanvas._elem.bind('mousemove', {plot:this}, this.onMouseMove);
            this.eventCanvas._elem.bind('mouseenter', {plot:this}, this.onMouseEnter);
            this.eventCanvas._elem.bind('mouseleave', {plot:this}, this.onMouseLeave);
        };
        
        function getEventPosition(ev) {
    	    var plot = ev.data.plot;
    	    var xaxis = plot.axes.xaxis;
    	    var x2axis = plot.axes.x2axis;
    	    var yaxis = plot.axes.yaxis;
    	    var y2axis = plot.axes.y2axis;
    	    var offsets = plot.eventCanvas._elem.offset();
    	    var gridPos = {x:ev.pageX - offsets.left, y:ev.pageY - offsets.top};
            // var dataPos = {x1y1:{x:null, y:null}, x1y2:{x:null, y:null}, x2y1:{x:null, y:null}, x2y2:{x:null, y:null}};
    	    var dataPos = {xaxis:null, yaxis:null, x2axis:null, y2axis:null};
    	    
    	    if (xaxis.show) {
    	        dataPos.xaxis = xaxis.series_p2u(gridPos.x);
    	    }
    	    if (yaxis.show) {
    	        dataPos.yaxis = yaxis.series_p2u(gridPos.y);
    	    }
    	    if (x2axis.show) {
    	        dataPos.x2axis = x2axis.series_p2u(gridPos.x);
    	    }
    	    if (y2axis.show) {
    	        dataPos.y2axis = y2axis.series_p2u(gridPos.y);
    	    }

            return ({offsets:offsets, gridPos:gridPos, dataPos:dataPos});
        }
        
        function getNeighborPoint(plot, x, y) {
            // won't you be mine?
            var ret = null;
            var s, i, d0, d, j;
            var threshold;
            for (var i=0; i<plot.series.length; i++) {
                s = plot.series[i];
                if (s.show) {
                    threshold = s.markerRenderer.size/2+4;
                    for (var j=0; j<s.gridData.length; j++) {
                       p = s.gridData[j];
                       d = Math.sqrt( (x-p[0]) * (x-p[0]) + (y-p[1]) * (y-p[1]) );
                       if (d <= threshold && (d <= d0 || d0 == null)) {
                           d0 = d;
                           ret = {seriesIndex: i, pointIndex:j, gridData:p, data:s.data[j]};
                       }
                    } 
                }
            }
            return ret;
        }
        
        this.onClick = function(ev) {
            // Event passed in is unnormalized and will have data attribute.
            // Event passed out in normalized and won't have data attribute.
            var positions = getEventPosition(ev);
            var p = ev.data.plot;
            var neighbor = getNeighborPoint(p, positions.gridPos.x, positions.gridPos.y);
    	    ev.data.plot.eventCanvas._elem.trigger('jqplotClick', [positions.gridPos, positions.dataPos, neighbor, p]);
        };
        
        this.onMouseDown = function(ev) {
            var positions = getEventPosition(ev);
            var p = ev.data.plot;
            var neighbor = getNeighborPoint(p, positions.gridPos.x, positions.gridPos.y);
    	    ev.data.plot.eventCanvas._elem.trigger('jqplotMouseDown', [positions.gridPos, positions.dataPos, neighbor, p]);
        };
        
        this.onMouseUp = function(ev) {
            var positions = getEventPosition(ev);
    	    ev.data.plot.eventCanvas._elem.trigger('jqplotMouseUp', [positions.gridPos, positions.dataPos, null, ev.data.plot]);
        };
        
        this.onMouseMove = function(ev) {
            var positions = getEventPosition(ev);
            var p = ev.data.plot;
            var neighbor = getNeighborPoint(p, positions.gridPos.x, positions.gridPos.y);
    	    ev.data.plot.eventCanvas._elem.trigger('jqplotMouseMove', [positions.gridPos, positions.dataPos, neighbor, p]);
        };
        
        this.onMouseEnter = function(ev) {
            var positions = getEventPosition(ev);
            var p = ev.data.plot;
    	    ev.data.plot.eventCanvas._elem.trigger('jqplotMouseEnter', [positions.gridPos, positions.dataPos, null, p]);
        };
        
        this.onMouseLeave = function(ev) {
            var positions = getEventPosition(ev);
            var p = ev.data.plot;
    	    ev.data.plot.eventCanvas._elem.trigger('jqplotMouseLeave', [positions.gridPos, positions.dataPos, null, p]);
        };
        
        this.drawSeries = function(sctx, options){
            // first clear the canvas, since we are redrawing all series.
            sctx.clearRect(0,0,sctx.canvas.width, sctx.canvas.height);
            for (var i=0; i<this.series.length; i++) {
                this.series[i].draw(sctx, options);
            }
        };
    }

    // convert a hex color string to rgb string.
    // h - 3 or 6 character hex string, with or without leading #
    // a - optional alpha
    $.jqplot.hex2rgb = function(h, a) {
        h = h.replace('#', '');
        if (h.length == 3) {
            h = h[0]+h[0]+h[1]+h[1]+h[2]+h[2];
        }
        var rgb;
        rgb = 'rgba('+parseInt(h.slice(0,2), 16)+', '+parseInt(h.slice(2,4), 16)+', '+parseInt(h.slice(4,6), 16);
        if (a) {
            rgb += ', '+a;
        }
        rgb += ')';
        return rgb;
    };
    
    // convert an rgb color spec to a hex spec.  ignore any alpha specification.
    $.jqplot.rgb2hex = function(s) {
        var pat = /rgba?\( *([0-9]{1,3}\.?[0-9]*%?) *, *([0-9]{1,3}\.?[0-9]*%?) *, *([0-9]{1,3}\.?[0-9]*%?) *(?:, *[0-9.]*)?\)/;
        var m = s.match(pat);
        var h = '#';
        for (i=1; i<4; i++) {
            var temp;
            if (m[i].search(/%/) != -1) {
                temp = parseInt(255*m[i]/100, 10).toString(16);
                if (temp.length == 1) {
                    temp = '0'+temp;
                }
            }
            else {
                temp = parseInt(m[i], 10).toString(16);
                if (temp.length == 1) {
                    temp = '0'+temp;
                }
            }
            h += temp;
        }
        return h;
    };
    
    // given a css color spec, return an rgb css color spec
    $.jqplot.normalize2rgb = function(s, a) {
        if (s.search(/^ *rgba?\(/) != -1) {
            return s; 
        }
        else if (s.search(/^ *#?[0-9a-fA-F]?[0-9a-fA-F]/) != -1) {
            return $.jqplot.hex2rgb(s, a);
        }
        else {
            throw 'invalid color spec';
        }
    };
    
    // extract the r, g, b, a color components out of a css color spec.
    $.jqplot.getColorComponents = function(s) {
        var rgb = $.jqplot.normalize2rgb(s);
        var pat = /rgba?\( *([0-9]{1,3}\.?[0-9]*%?) *, *([0-9]{1,3}\.?[0-9]*%?) *, *([0-9]{1,3}\.?[0-9]*%?) *,? *([0-9.]* *)?\)/;
        var m = rgb.match(pat);
        var ret = [];
        for (i=1; i<4; i++) {
            if (m[i].search(/%/) != -1) {
                ret[i-1] = parseInt(255*m[i]/100, 10);
            }
            else {
                ret[i-1] = parseInt(m[i], 10);
            }
        }
        ret[3] = parseFloat(m[4]) ? parseFloat(m[4]) : 1.0;
        return ret;
    };
        
	// Convienence function that won't hang IE.
	$.jqplot.log = function() {
	    if (window.console && $.jqplot.debug) {
	       if (arguments.length == 1) {
	           console.log (arguments[0]);
	        }
	       else {
	           console.log(arguments);
	        }
	    }
	};
	var log = $.jqplot.log;
	

    // class: $.jqplot.AxisTickRenderer
    // A "tick" object showing the value of a tick/gridline on the plot.
    $.jqplot.AxisTickRenderer = function(options) {
        // Group: Properties
        $.jqplot.ElemContainer.call(this);
        // prop: mark
        // tick mark on the axis.  One of 'inside', 'outside', 'cross', '' or null.
        this.mark = 'outside';
        // prop: showMark
        // wether or not to show the mark on the axis.
        this.showMark = true;
        // prop: showGridline
        // wether or not to draw the gridline on the grid at this tick.
        this.showGridline = true;
        // prop: isMinorTick
        // if this is a minor tick.
        this.isMinorTick = false;
        // prop: size
        // Length of the tick beyond the grid in pixels.
        this.size = 4;
        // prop:  markSize
        // Length of the tick marks in pixels.  For 'cross' style, length
        // will be stoked above and below axis, so total length will be twice this.
        this.markSize = 4;
        // prop: show
        // wether or not to show the tick (mark and label).
        this.show = true;
        // prop: showLabel
        // wether or not to show the label.
        this.showLabel = true;
        this.label = '';
        this.value = null;
        this._styles = {};
        // prop: formatter
        // A class of a formatter for the tick text.  sprintf by default.
        this.formatter = $.jqplot.DefaultTickFormatter;
        // prop: formatString
        // string passed to the formatter.
        this.formatString = '';
        // prop: fontFamily
        // css spec for the font-family css attribute.
        this.fontFamily;
        // prop: fontSize
        // css spec for the font-size css attribute.
        this.fontSize;
        // prop: textColor
        // css spec for the color attribute.
        this.textColor;
        this._elem;
        
        $.extend(true, this, options);
    };
    
    $.jqplot.AxisTickRenderer.prototype.init = function(options) {
        $.extend(true, this, options);
    };
    
    $.jqplot.AxisTickRenderer.prototype = new $.jqplot.ElemContainer();
    $.jqplot.AxisTickRenderer.prototype.constructor = $.jqplot.AxisTickRenderer;
    
    $.jqplot.AxisTickRenderer.prototype.setTick = function(value, axisName, isMinor) {
        this.value = value;
        if (isMinor) {
        	this.isMinorTick = true;
        }
        return this;
    };
    
    $.jqplot.AxisTickRenderer.prototype.draw = function() {
        if (!this.label) {
        	this.label = this.formatter(this.formatString, this.value);
        }
        style='';
        if (Number(this.label)) {
            style='style="white-space:nowrap;" ';
        }
        this._elem = $('<div '+style+'class="jqplot-'+this.axis+'-tick">'+this.label+'</div>');
        for (var s in this._styles) {
            this._elem.css(s, this._styles[s]);
        }
        if (this.fontFamily) {
        	this._elem.css('font-family', this.fontFamily);
        }
        if (this.fontSize) {
        	this._elem.css('font-size', this.fontSize);
        }
        if (this.textColor) {
        	this._elem.css('color', this.textColor);
        }
        return this._elem;
    };
        
    $.jqplot.DefaultTickFormatter = function (format, val) {
        if (typeof val == 'number') {
            if (!format) {
            	format = '%.1f';
            }
            return $.jqplot.sprintf(format, val);
        }
        else {
            return String(val);
        }
    };
    
    $.jqplot.AxisTickRenderer.prototype.pack = function() {
    };
     
    // Class: $.jqplot.CanvasGridRenderer
    // The default jqPlot grid renderer, creating a grid on a canvas element.
    // The renderer has no additional options beyond the <Grid> class.
    $.jqplot.CanvasGridRenderer = function(){
        this.shadowRenderer = new $.jqplot.ShadowRenderer();
    };
    
    // called with context of Grid object
    $.jqplot.CanvasGridRenderer.prototype.init = function(options) {
        this._ctx;
        $.extend(true, this, options);
        // set the shadow renderer options
        var sopts = {lineJoin:'miter', lineCap:'round', fill:false, isarc:false, angle:this.shadowAngle, offset:this.shadowOffset, alpha:this.shadowAlpha, depth:this.shadowDepth, lineWidth:this.shadowWidth, closePath:false};
        this.renderer.shadowRenderer.init(sopts);
    };
    
    // called with context of Grid.
    $.jqplot.CanvasGridRenderer.prototype.createElement = function() {
        var elem = document.createElement('canvas');
        var w = this._plotDimensions.width;
        var h = this._plotDimensions.height;
        elem.width = w;
        elem.height = h;
        this._elem = $(elem);
        this._elem.addClass('jqplot-grid-canvas');
        this._elem.css({ position: 'absolute', left: 0, top: 0 });
        // borrowed from flot by Ole Laursen
        if ($.browser.msie) {
            window.G_vmlCanvasManager.init_(document);
        }
        if ($.browser.msie) {
            elem = window.G_vmlCanvasManager.initElement(elem);
        }
        this._top = this._offsets.top;
        this._bottom = h - this._offsets.bottom;
        this._left = this._offsets.left;
        this._right = w - this._offsets.right;
        this._width = this._right - this._left;
        this._height = this._bottom - this._top;
        return this._elem;
    };
    
    $.jqplot.CanvasGridRenderer.prototype.draw = function() {
        this._ctx = this._elem.get(0).getContext("2d");
        var ctx = this._ctx;
        var axes = this._axes;
        // Add the grid onto the grid canvas.  This is the bottom most layer.
        ctx.save();
        ctx.fillStyle = this.background;
        ctx.fillRect(this._left, this._top, this._width, this._height);
        
        if (this.drawGridlines) {
            ctx.save();
            ctx.lineJoin = 'miter';
            ctx.lineCap = 'round';
            ctx.lineWidth = 1;
            ctx.strokeStyle = '#cccccc';
            for (var name in axes) {
                var axis = axes[name];
                var ticks = axis._ticks;
                if (axis.show) {
                    for (var i=0; i<ticks.length; i++) {
                        var t = axis._ticks[i];
                        if (t.show && t.showGridline) {
                            var pos = Math.round(axis.u2p(t.value)) + 0.5;
                            switch (name) {
                                case 'xaxis':
                                    drawLine(pos, this._top, pos, this._bottom);
                                    break;
                                case 'yaxis':
                                    drawLine(this._right, pos, this._left, pos);
                                    break;
                                case 'x2axis':
                                    drawLine(pos, this._bottom, pos, this._top);
                                    break;
                                case 'y2axis':
                                    drawLine(this._left, pos, this._right, pos);
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                }
            }
            ctx.restore();
        }
        
        function drawLine(bx, by, ex, ey) {
            ctx.beginPath();
            ctx.moveTo(bx, by);
            ctx.lineTo(ex, ey);
            ctx.stroke();
        }
        // Now draw the tick marks.
        ctx.save();
        ctx.lineJoin = 'miter';
        ctx.lineCap = 'round';
        ctx.lineWidth = 1;
        ctx.strokeStyle = '#cccccc';
        for (var name in axes) {
            var axis = axes[name];
            if (axis.show) {
                var t = axis._ticks;
                for (var i=0; i<t.length; i++) {
                    if (t[i].show && t[i].showMark && t[i].mark) {
                        s = t[i].markSize;
                        m = t[i].mark;
                        var pos = Math.round(axis.u2p(t[i].value)) + 0.5;
                        var b, e;
                        switch (name)     {
                            case 'xaxis':
                                switch (m) {
                                    case 'inside':
                                        b = this._bottom-s;
                                        e = this._bottom;
                                        break;
                                    case 'outside':
                                        b = this._bottom;
                                        e = this._bottom+s;
                                        break;
                                    case 'cross':
                                        b = this._bottom-s;
                                        e = this._bottom+s;
                                        break;
                                    default:
                                        b = this._bottom;
                                        e = this._bottom+s;
                                        break;
                                }
                                drawLine(pos, b, pos, e);
                                break;
                            case 'yaxis':
                                switch (m) {
                                    case 'outside':
                                        b = this._left-s;
                                        e = this._left;
                                        break;
                                    case 'inside':
                                        b = this._left;
                                        e = this._left+s;
                                        break;
                                    case 'cross':
                                        b = this._left-s;
                                        e = this._left+s;
                                        break;
                                    default:
                                        b = this._left-s;
                                        e = this._left;
                                        break;
                                }
                                drawLine(b, pos, e, pos);
                                break;
                            case 'x2axis':
                                switch (m) {
                                    case 'outside':
                                        b = this._top-s;
                                        e = this._top;
                                        break;
                                    case 'inside':
                                        b = this._top;
                                        e = this._top+s;
                                        break;
                                    case 'cross':
                                        b = this._top-s;
                                        e = this._top+s;
                                        break;
                                    default:
                                        b = this._top-s;
                                        e = this._top;
                                        break;
                                }
                                drawLine(pos, b, pos, e);
                                break;
                            case 'y2axis':
                                switch (m) {
                                    case 'inside':
                                        b = this._right-s;
                                        e = this._right;
                                        break;
                                    case 'outside':
                                        b = this._right;
                                        e = this._right+s;
                                        break;
                                    case 'cross':
                                        b = this._right-s;
                                        e = this._right+s;
                                        break;
                                    default:
                                        b = this._right;
                                        e = this._right+s;
                                        break;
                                }
                                drawLine(b, pos, e, pos);
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }
        ctx.restore();
        ctx.lineWidth = this.borderWidth;
        ctx.strokeStyle = this.borderColor;
        ctx.strokeRect(this._left, this._top, this._width, this._height);
        
        // now draw the shadow
        if (this.shadow) {
            var points = [[this._left, this._bottom], [this._right, this._bottom], [this._right, this._top]];
            this.renderer.shadowRenderer.draw(ctx, points);
        }
    
        ctx.restore();
    };
 
    // Class: $.jqplot.DivTitleRenderer
    // The default title renderer for jqPlot.  This class has no options beyond the <Title> class. 
    $.jqplot.DivTitleRenderer = function() {
    };
    
    $.jqplot.DivTitleRenderer.prototype.init = function(options) {
        $.extend(true, this, options);
    };
    
    $.jqplot.DivTitleRenderer.prototype.draw = function() {
        var r = this.renderer;
        if (!this.text) {
            this.show = false;
            this._elem = $('<div style="height:0px;width:0px;"></div>');
        }
        else if (this.text) {
            // don't trust that a stylesheet is present, set the position.
            var styletext = 'position:absolute;top:0px;left:0px;';
            styletext += (this._plotWidth) ? 'width:'+this._plotWidth+'px;' : '';
            styletext += (this.fontFamily) ? 'font-family:'+this.fontFamily+';' : '';
            styletext += (this.fontSize) ? 'font-size:'+this.fontSize+';' : '';
            styletext += (this.textAlign) ? 'text-align:'+this.textAlign+';' : 'text-align:center;';
            styletext += (this.textColor) ? 'color:'+this.textColor+';' : '';
            this._elem = $('<div class="jqplot-title" style="'+styletext+'">'+this.text+'</div>');
        }
        
        return this._elem;
    };
    
    $.jqplot.DivTitleRenderer.prototype.pack = function() {
        // nothing to do here
    };
  
    // Class: $.jqplot.LineRenderer
    // The default line renderer for jqPlot, this class has no options beyond the <Series> class.
    // Draws series as a line.
    $.jqplot.LineRenderer = function(){
        this.shapeRenderer = new $.jqplot.ShapeRenderer();
        this.shadowRenderer = new $.jqplot.ShadowRenderer();
    };
    
    // called with scope of series.
    $.jqplot.LineRenderer.prototype.init = function(options) {
        $.extend(true, this.renderer, options);
        // set the shape renderer options
        var opts = {lineJoin:'miter', lineCap:'round', fill:this.fill, isarc:false, strokeStyle:this.color, fillStyle:this.color, lineWidth:this.lineWidth, closePath:this.fill};
        this.renderer.shapeRenderer.init(opts);
        // set the shadow renderer options
        var sopts = {lineJoin:'miter', lineCap:'round', fill:this.fill, isarc:false, angle:this.shadowAngle, offset:this.shadowOffset, alpha:this.shadowAlpha, depth:this.shadowDepth, lineWidth:this.lineWidth, closePath:this.fill};
        this.renderer.shadowRenderer.init(sopts);
    };
    
    // Method: setGridData
    // converts the user data values to grid coordinates and stores them
    // in the gridData array.
    // Called with scope of a series.
    $.jqplot.LineRenderer.prototype.setGridData = function() {
        // recalculate the grid data
        var xp = this._xaxis.series_u2p;
        var yp = this._yaxis.series_u2p;
        var data = this._plotData;
        var pdata = this._prevPlotData;
        this.gridData = [];
        for (var i=0; i<this.data.length; i++) {
            this.gridData.push([xp.call(this._xaxis, data[i][0]), yp.call(this._yaxis, data[i][1])]);
            if (pdata.length > i) {
                this._prevGridData.push([xp.call(this._xaxis, pdata[i][0]), yp.call(this._yaxis, pdata[i][1])])
            }
        }
    };
    
    // Method: makeGridData
    // converts any arbitrary data values to grid coordinates and
    // returns them.  This method exists so that plugins can use a series'
    // linerenderer to generate grid data points without overwriting the
    // grid data associated with that series.
    // Called with scope of a series.
    $.jqplot.LineRenderer.prototype.makeGridData = function(data) {
        // recalculate the grid data
        var xp = this._xaxis.series_u2p;
        var yp = this._yaxis.series_u2p;
        var gd = [];
        var pgd = [];
        for (var i=0; i<data.length; i++) {
            gd.push([xp.call(this._xaxis, data[i][0]), yp.call(this._yaxis, data[i][1])]);
        }
        return gd;
    };
    

    // called within scope of series.
    $.jqplot.LineRenderer.prototype.draw = function(ctx, gd, options, plot) {
        var i;
        var opts = (options != undefined) ? options : {};
        var shadow = (opts.shadow != undefined) ? opts.shadow : this.shadow;
        var showLine = (opts.showLine != undefined) ? opts.showLine : this.showLine;
        var fill = (opts.fill != undefined) ? opts.fill : this.fill;
        ctx.save();
        if (showLine) {
            // if we fill, we'll have to add points to close the curve.
            if (fill) {
                // if not stacked, fill down to axis
                if (this.index == 0 || !this._stack) {
                    var gridymin = this._yaxis.series_u2p(this._yaxis.min) - this.gridBorderWidth / 2;
                    // IE doesn't return new length on unshift
                    gd.unshift([gd[0][0], gridymin]);
                    len = gd.length;
                    gd.push([gd[len - 1][0], gridymin]);                    
                }
                // if stacked, fill to line below 
                else {
                    var prev = this._prevGridData;
                    for (var i=prev.length-1; i>-1; i--) {
                        gd.push(prev[i]);
                    }
                }
            }
            
            this.renderer.shapeRenderer.draw(ctx, gd, opts);                

            // now draw the shadows
            if (shadow) {
                this.renderer.shadowRenderer.draw(ctx, gd, opts);
            }
        }
        
        // now draw the markers
        if (this.markerRenderer.show) {
            for (i=0; i<gd.length; i++) {
                this.markerRenderer.draw(gd[i][0], gd[i][1], ctx, opts);
            }
        }
        
        ctx.restore();
    };  
    
    // class: $.jqplot.LinearAxisRenderer
    // The default jqPlot axis renderer, creating a numeric axis.
    // The renderer has no additional options beyond the <Axis> object.
    $.jqplot.LinearAxisRenderer = function() {
    };
    
    // called with scope of axis object.
    $.jqplot.LinearAxisRenderer.prototype.init = function(options){
        // prop: tickRenderer
        // A class of a rendering engine for creating the ticks labels displayed on the plot, 
        // See <$.jqplot.AxisTickRenderer>.
        this.tickRenderer = $.jqplot.AxisTickRenderer;
        $.extend(true, this, options);
        var db = this._dataBounds;
        // Go through all the series attached to this axis and find
        // the min/max bounds for this axis.
        for (var i=0; i<this._series.length; i++) {
            var s = this._series[i];
            var d = s._plotData;
            
            for (var j=0; j<d.length; j++) { 
                if (this.name == 'xaxis' || this.name == 'x2axis') {
                    if (d[j][0] < db.min || db.min == null) {
                    	db.min = d[j][0];
                    }
                    if (d[j][0] > db.max || db.max == null) {
                    	db.max = d[j][0];
                    }
                }              
                else {
                    if (d[j][1] < db.min || db.min == null) {
                    	db.min = d[j][1];
                    }
                    if (d[j][1] > db.max || db.max == null) {
                    	db.max = d[j][1];
                    }
                }              
            }
        }
    };
    

    $.jqplot.LinearAxisRenderer.prototype.draw = function(ctx) {
        if (this.show) {
            // populate the axis label and value properties.
            this.renderer.createTicks.call(this);
            // fill a div with axes labels in the right direction.
            // Need to pregenerate each axis to get it's bounds and
            // position it and the labels correctly on the plot.
            var dim=0;
            var temp;
            
            this._elem = $('<div class="jqplot-axis jqplot-'+this.name+'" style="position:absolute;"></div>');
    
            if (this.showTicks) {
                var t = this._ticks;
                for (var i=0; i<t.length; i++) {
                    var tick = t[i];
                    if (tick.showLabel && (!tick.isMinorTick || this.showMinorTicks)) {
                        var elem = tick.draw(ctx);
                        elem.appendTo(this._elem);
                    }
                }
            }
        }
        return this._elem;
    };
    
    $.jqplot.LinearAxisRenderer.prototype.set = function() { 
        var dim = 0;
        var temp; 
        if (this.show && this.showTicks) {
            var t = this._ticks;
            for (var i=0; i<t.length; i++) {
                var tick = t[i];
                if (tick.showLabel && (!tick.isMinorTick || this.showMinorTicks)) {
                    if (this.name == 'xaxis' || this.name == 'x2axis') {
                        temp = tick._elem.outerHeight(true);
                    }
                    else {
                        temp = tick._elem.outerWidth(true);
                    }
                    if (temp > dim) {
                    	dim = temp;
                    }
                }
            }
            if (this.name == 'xaxis') {
            	this._elem.css({'height':dim+'px', left:'0px', bottom:'0px'});
            }
            else if (this.name == 'x2axis') {
            	this._elem.css({'height':dim+'px', left:'0px', top:'0px'});
            }
            else if (this.name == 'yaxis') {
            	this._elem.css({'width':dim+'px', left:'0px', top:'0px'});
            }
            else if (this.name == 'y2axis') {
            	this._elem.css({'width':dim+'px', right:'0px', top:'0px'});
            }
        }  
    };
    
    // called with scope of axis
    $.jqplot.LinearAxisRenderer.prototype.createTicks = function() {
        // we're are operating on an axis here
        var ticks = this._ticks;
        var userTicks = this.ticks;
        var name = this.name;
        // databounds were set on axis initialization.
        var db = this._dataBounds;
        var dim, interval;
        var min, max;
        var pos1, pos2;
        var tt, i;
        
        // if we already have ticks, use them.
        // ticks must be in order of increasing value.
        
        if (userTicks.length) {
            // ticks could be 1D or 2D array of [val, val, ,,,] or [[val, label], [val, label], ...] or mixed
            for (i=0; i<userTicks.length; i++){
                var ut = userTicks[i];
                var t = new this.tickRenderer(this.tickOptions);
                if (ut.constructor == Array) {
                    t.value = ut[0];
                    t.label = ut[1];
                    if (!this.showTicks) {
                        t.showLabel = false;
                        t.showMark = false;
                    }
                    else if (!this.showTickMarks) {
                    	t.showMark = false;
                    }
                    t.setTick(ut[0], this.name);
                    this._ticks.push(t);
                }
                
                else {
                    t.value = ut;
                    if (!this.showTicks) {
                        t.showLabel = false;
                        t.showMark = false;
                    }
                    else if (!this.showTickMarks) {
                    	t.showMark = false;
                    }
                    t.setTick(ut, this.name);
                    this._ticks.push(t);
                }
            }
            this.numberTicks = userTicks.length;
            this.min = this._ticks[0].value;
            this.max = this._ticks[this.numberTicks-1].value;
            this.tickInterval = (this.max - this.min) / (this.numberTicks - 1);
        }
        
        // we don't have any ticks yet, let's make some!
        else {
            if (name == 'xaxis' || name == 'x2axis') {
                dim = this._plotDimensions.width;
            }
            else {
                dim = this._plotDimensions.height;
            }
            
            // if min, max and number of ticks specified, user can't specify interval.
            if (this.min != null && this.max != null && this.numberTicks != null) {
                this.tickInterval = null;
            }
            
            // if max, min, and interval specified and interval won't fit, ignore interval.
            if (this.min != null && this.max != null && this.tickInterval != null) {
                if (parseInt((this.max-this.min)/this.tickInterval, 10) != (this.max-this.min)/this.tickInterval) {
                    this.tickInterval = null;
                }
            }
        
            min = ((this.min != null) ? this.min : db.min);
            max = ((this.max != null) ? this.max : db.max);
            
            // if min and max are same, space them out a bit
            if (min == max) {
                var adj = 0.05;
                if (min != 0) {
                    adj = Math.max(Math.log(min)/Math.LN10, 0.05);
                }
                min -= adj;
                max += adj;
            }

            var range = max - min;
            var rmin, rmax;
        
            rmin = (this.min != null) ? this.min : min - range/2*(this.pad - 1);
            rmax = (this.max != null) ? this.max : max + range/2*(this.pad - 1);
            this.min = rmin;
            this.max = rmax;
            range = this.max - this.min;
    
            if (this.numberTicks == null){
                // if tickInterval is specified by user, we will ignore computed maximum.
                // max will be equal or greater to fit even # of ticks.
                if (this.tickInterval != null) {
                    this.numberTicks = Math.ceil((this.max - this.min)/this.tickInterval);
                    this.max = this.min + this.tickInterval*this.numberTicks;
                }
                if (dim > 100) {
                    this.numberTicks = parseInt(3+(dim-100)/75, 10);
                }
                else {
                    this.numberTicks = 2;
                }
            }
            
            if (this.tickInterval == null) {
            	this.tickInterval = range / (this.numberTicks-1);
            }
            for (var i=0; i<this.numberTicks; i++){
                tt = this.min + i * this.tickInterval;
                var t = new this.tickRenderer(this.tickOptions);
                // var t = new $.jqplot.AxisTickRenderer(this.tickOptions);
                if (!this.showTicks) {
                    t.showLabel = false;
                    t.showMark = false;
                }
                else if (!this.showTickMarks) {
                	t.showMark = false;
                }
                t.setTick(tt, this.name);
                this._ticks.push(t);
            }
        }
    };
    
    $.jqplot.LinearAxisRenderer.prototype.pack = function(pos, offsets) {
        var ticks = this._ticks;
        var max = this.max;
        var min = this.min;
        var offmax = offsets.max;
        var offmin = offsets.min;
        
        for (var p in pos) {
            this._elem.css(p, pos[p]);
        }
        
        this._offsets = offsets;
        // pixellength will be + for x axes and - for y axes becasue pixels always measured from top left.
        var pixellength = offmax - offmin;
        var unitlength = max - min;
        
        // point to unit and unit to point conversions references to Plot DOM element top left corner.
        this.p2u = function(p){
            return (p - offmin) * unitlength / pixellength + min;
        };
        
        this.u2p = function(u){
            return (u - min) * pixellength / unitlength + offmin;
        };
                
        if (this.name == 'xaxis' || this.name == 'x2axis'){
            this.series_u2p = function(u){
                return (u - min) * pixellength / unitlength;
            };
            this.series_p2u = function(p){
                return p * unitlength / pixellength + min;
            };
        }
        
        else {
            this.series_u2p = function(u){
                return (u - max) * pixellength / unitlength;
            };
            this.series_p2u = function(p){
                return p * unitlength / pixellength + max;
            };
        }
        
        if (this.show) {
            if (this.name == 'xaxis' || this.name == 'x2axis') {
                for (i=0; i<ticks.length; i++) {
                    var t = ticks[i];
                    if (t.show && t.showLabel) {
                        var shim = t.getWidth()/2;
                        var val = this.u2p(t.value) - shim + 'px';
                        t._elem.css('left', val);
                        t.pack();
                    }
                }
            }
            else {
                for (i=0; i<ticks.length; i++) {
                    var t = ticks[i];
                    if (t.show && t.showLabel) {
                        var shim = t.getHeight()/2;
                        var val = this.u2p(t.value) - shim + 'px';
                        t._elem.css('top', val);
                        t.pack();
                    }
                }
            }
        }
    };


	// class: $.jqplot.MarkerRenderer
	// The default jqPlot marker renderer, rendering the points on the line.
    $.jqplot.MarkerRenderer = function(options){
        // Group: Properties
        
        // prop: show
        // wether or not to show the marker.
        this.show = true;
        // prop: style
        // One of diamond, circle, square, x, plus, dash, filledDiamond, filledCircle, filledSquare
        this.style = 'filledCircle';
        // prop: lineWidth
        // size of the line for non-filled markers.
        this.lineWidth = 2;
        // prop: size
        // Size of the marker (diameter or circle, length of edge of square, etc.)
        this.size = 9.0;
        // prop: color
        // color of marker.  Will be set to color of series by default on init.
        this.color = '#666666';
        // prop: shadow
        // wether or not to draw a shadow on the line
        this.shadow = true;
        // prop: shadowAngle
        // Shadow angle in degrees
        this.shadowAngle = 45;
        // prop: shadowOffset
        // Shadow offset from line in pixels
        this.shadowOffset = 1;
        // prop: shadowDepth
        // Number of times shadow is stroked, each stroke offset shadowOffset from the last.
        this.shadowDepth = 3;
        // prop: shadowAlpha
        // Alpha channel transparency of shadow.  0 = transparent.
        this.shadowAlpha = '0.07';
        // prop: shadowRenderer
        // Renderer that will draws the shadows on the marker.
        this.shadowRenderer = new $.jqplot.ShadowRenderer();
        // prop: shapeRenderer
        // Renderer that will draw the marker.
        this.shapeRenderer = new $.jqplot.ShapeRenderer();
        
        $.extend(true, this, options);
    };
    
    $.jqplot.MarkerRenderer.prototype.init = function(options) {
        $.extend(true, this, options);
        var sdopt = {angle:this.shadowAngle, offset:this.shadowOffset, alpha:this.shadowAlpha, lineWidth:this.lineWidth, depth:this.shadowDepth, closePath:true};
        if (this.style.indexOf('filled') != -1) {
            sdopt.fill = true;
        }
        if (this.style.indexOf('ircle') != -1) {
            sdopt.isarc = true;
            sdopt.closePath = false;
        }
        this.shadowRenderer.init(sdopt);
        
        var shopt = {fill:false, isarc:false, strokeStyle:this.color, fillStyle:this.color, lineWidth:this.lineWidth, closePath:true};
        if (this.style.indexOf('filled') != -1) {
            shopt.fill = true;
        }
        if (this.style.indexOf('ircle') != -1) {
            shopt.isarc = true;
            shopt.closePath = false;
        }
        this.shapeRenderer.init(shopt);
    };
    
    $.jqplot.MarkerRenderer.prototype.drawDiamond = function(x, y, ctx, fill) {
        var stretch = 1.2;
        var dx = this.size/2/stretch;
        var dy = this.size/2*stretch;
        var points = [[x-dx, y], [x, y+dy], [x+dx, y], [x, y-dy]];
        this.shapeRenderer.draw(ctx, points);
        
        if (this.shadow) {
            this.shadowRenderer.draw(ctx, points);
        }
        
        ctx.restore();
    };
    
    $.jqplot.MarkerRenderer.prototype.drawSquare = function(x, y, ctx, fill) {
        var stretch = 1.0;
        var dx = this.size/2/stretch;
        var dy = this.size/2*stretch;
        var points = [[x-dx, y-dy], [x-dx, y+dy], [x+dx, y+dy], [x+dx, y-dy]];
        this.shapeRenderer.draw(ctx, points);
        
        if (this.shadow) {
            this.shadowRenderer.draw(ctx, points);
        }
        
        ctx.restore();
    };
    
    $.jqplot.MarkerRenderer.prototype.drawCircle = function(x, y, ctx, fill) {
        var radius = this.size/2;
        var end = 2*Math.PI;
        var points = [x, y, radius, 0, end, true];
        this.shapeRenderer.draw(ctx, points);
        
        if (this.shadow) {
            this.shadowRenderer.draw(ctx, points);
        }
        ctx.restore();
    };
    
    $.jqplot.MarkerRenderer.prototype.draw = function(x, y, ctx, options) {
        switch (this.style) {
            case 'diamond':
                this.drawDiamond(x,y,ctx, false);
                break;
            case 'filledDiamond':
                this.drawDiamond(x,y,ctx, true);
                break;
            case 'circle':
                this.drawCircle(x,y,ctx, false);
                break;
            case 'filledCircle':
                this.drawCircle(x,y,ctx, true);
                break;
            case 'square':
                this.drawSquare(x,y,ctx, false);
                break;
            case 'filledSquare':
                this.drawSquare(x,y,ctx, true);
                break;
            default:
                this.drawDiamond(x,y,ctx, false);
                break;
        }
    };
    
	// class: $.jqplot.shadowRenderer
	// The default jqPlot shadow renderer, rendering shadows behind shapes.
    $.jqplot.ShadowRenderer = function(options){ 
        // Group: Properties
        
        // prop: angle
        // Angle of the shadow in degrees.  Measured counter-clockwise from the x axis.
        this.angle = 45;
        // prop: offset
        // Pixel offset at the given shadow angle of each shadow stroke from the last stroke.
        this.offset = 1;
        // prop: alpha
        // alpha transparency of shadow stroke.
        this.alpha = 0.07;
        // prop: lineWidth
        // width of the shadow line stroke.
        this.lineWidth = 1.5;
        // prop: lineJoin
        // How line segments of the shadow are joined.
        this.lineJoin = 'miter';
        // prop: lineCap
        // how ends of the shadow line are rendered.
        this.lineCap = 'round';
        // prop; closePath
        // whether line path segment is closed upon itself.
        this.closePath = false;
        // prop: fill
        // whether to fill the shape.
        this.fill = false;
        // prop: depth
        // how many times the shadow is stroked.  Each stroke will be offset by offset at angle degrees.
        this.depth = 3;
        // prop: isarc
        // wether the shadow is an arc or not.
        this.isarc = false;
        
        $.extend(true, this, options);
    };
    
    $.jqplot.ShadowRenderer.prototype.init = function(options) {
        $.extend(true, this, options);
    };
    
    // function: draw
    // draws an transparent black (i.e. gray) shadow.
    //
    // ctx - canvas drawing context
    // points - array of points or [x, y, radius, start angle (rad), end angle (rad)]
    $.jqplot.ShadowRenderer.prototype.draw = function(ctx, points, options) {
        ctx.save();
        ctx.lineWidth = this.lineWidth;
        ctx.lineJoin = this.lineJoin;
        ctx.lineCap = this.lineCap;
        ctx.strokeStyle = 'rgba(0,0,0,'+this.alpha+')';
        ctx.fillStyle = 'rgba(0,0,0,'+this.alpha+')';
        for (var j=0; j<this.depth; j++) {
            ctx.translate(Math.cos(this.angle*Math.PI/180)*this.offset, Math.sin(this.angle*Math.PI/180)*this.offset);
            ctx.beginPath();
            if (this.isarc) {
                ctx.arc(points[0], points[1], points[2], points[3], points[4], true);                
            }
            else {
                ctx.moveTo(points[0][0], points[0][1]);
                for (var i=1; i<points.length; i++) {
                    ctx.lineTo(points[i][0], points[i][1]);
                }
                
            }
            if (this.closePath) {
            	ctx.closePath();
            }
            if (this.fill) {
            	ctx.fill();
            }
            else {
                ctx.stroke();
            }
        }
        ctx.restore();
    };
    
	// class: $.jqplot.shapeRenderer
	// The default jqPlot shape renderer.  Given a set of points will
	// plot them and either stroke a line (fill = false) or fill them (fill = true).
	// If a filled shape is desired, closePath = true must also be set to close
	// the shape.
    $.jqplot.ShapeRenderer = function(options){
        
        this.lineWidth = 1.5;
        // prop: lineJoin
        // How line segments of the shadow are joined.
        this.lineJoin = 'miter';
        // prop: lineCap
        // how ends of the shadow line are rendered.
        this.lineCap = 'round';
        // prop; closePath
        // whether line path segment is closed upon itself.
        this.closePath = false;
        // prop: fill
        // whether to fill the shape.
        this.fill = false;
        // prop: isarc
        // wether the shadow is an arc or not.
        this.isarc = false;
        // prop: strokeStyle
        // css color spec for the stoke style
        this.strokeStyle = '#999999';
        // prop: fillStyle
        // css color spec for the fill style.
        this.fillStyle = '#999999'; 
        
        $.extend(true, this, options);
    };
    
    $.jqplot.ShapeRenderer.prototype.init = function(options) {
        $.extend(true, this, options);
    };
    
    // function: draw
    // draws the shape.
    //
    // ctx - canvas drawing context
    // points - array of points for shapes or 
    // [x, y, radius, start angle (rad), end angle (rad)] for circles and arcs.
    $.jqplot.ShapeRenderer.prototype.draw = function(ctx, points, options) {
        ctx.save();
        var opts = (options != null) ? options : {};
        ctx.lineWidth = opts.lineWidth || this.lineWidth;
        ctx.lineJoin = opts.lineJoing || this.lineJoin;
        ctx.lineCap = opts.lineCap || this.lineCap;
        ctx.strokeStyle = (opts.strokeStyle || opts.color) || this.strokeStyle;
        ctx.fillStyle = opts.fillStyle || this.fillStyle;
        ctx.beginPath();
        if (this.isarc) {
            ctx.arc(points[0], points[1], points[2], points[3], points[4], true);                
        }
        else {
            ctx.moveTo(points[0][0], points[0][1]);
            for (var i=1; i<points.length; i++) {
                ctx.lineTo(points[i][0], points[i][1]);
            }
            
        }
        if (this.closePath) {
            ctx.closePath();
        }
        if (this.fill) {
        	ctx.fill();
        }
        else {
            ctx.stroke();
        }
        ctx.restore();
    };
    
    // class $.jqplot.TableLegendRenderer
    // The default legend renderer for jqPlot, this class has no options beyond the <Legend> class.
    $.jqplot.TableLegendRenderer.prototype.init = function(options) {
        $.extend(true, this, options);
    };
    
    $.jqplot.TableLegendRenderer.prototype.draw = function() {
        var legend = this;
        if (this.show) {
            var series = this._series;
            // make a table.  one line label per row.
            var ss = 'position:absolute;';
            ss += (this.background) ? 'background:'+this.background+';' : '';
            ss += (this.border) ? 'border:'+this.border+';' : '';
            ss += (this.fontSize) ? 'font-size:'+this.fontSize+';' : '';
            ss += (this.fontFamily) ? 'font-family:'+this.fontFamily+';' : '';
            ss += (this.textColor) ? 'color:'+this.textColor+';' : '';
            this._elem = $('<table class="jqplot-legend" style="'+ss+'"></table>');
        
            var pad = false;
            for (var i = 0; i< series.length; i++) {
                s = series[i];
                if (s.show) {
                    var lt = s.label.toString();
                    if (lt) {
                        var color = s.color;
                        if (s._stack && !s.fill) {
                            color = '';
                        }
                        addrow.call(this, lt, color, pad);
                        pad = true;
                    }
                    // let plugins add more rows to legend.  Used by trend line plugin.
                    for (var j=0; j<$.jqplot.addLegendRowHooks.length; j++) {
                        var item = $.jqplot.addLegendRowHooks[j].call(this, s);
                        if (item) {
                            addrow.call(this, item.label, item.color, pad);
                            pad = true;
                        } 
                    }
                }
            }
        }
        
        function addrow(label, color, pad) {
            var rs = (pad) ? this.rowSpacing : '0';
            var tr = $('<tr class="jqplot-legend"></tr>').appendTo(this._elem);
            $('<td class="jqplot-legend" style="vertical-align:middle;text-align:center;padding-top:'+rs+';">'+
                '<div style="border:1px solid #cccccc;padding:0.2em;">'+
                '<div style="width:1.2em;height:0.7em;background-color:'+color+';"></div>'+
                '</div></td>').appendTo(tr);
            $('<td class="jqplot-legend" style="vertical-align:middle;padding-top:'+rs+';">'+label+'</td>').appendTo(tr);
        }
        return this._elem;
    };
    
    $.jqplot.TableLegendRenderer.prototype.pack = function(offsets) {
        if (this.show) {
            // fake a grid for positioning
            var grid = {_top:offsets.top, _left:offsets.left, _right:offsets.right, _bottom:this._plotDimensions.height - offsets.bottom};      
            switch (this.location) {
                case 'nw':
                    var a = grid._left + this.xoffset;
                    var b = grid._top + this.yoffset;
                    this._elem.css('left', a);
                    this._elem.css('top', b);
                    break;
                case 'n':
                    var a = (offsets.left + (this._plotDimensions.width - offsets.right))/2 - this.getWidth()/2;
                    var b = grid._top + this.yoffset;
                    this._elem.css('left', a);
                    this._elem.css('top', b);
                    break;
                case 'ne':
                    var a = offsets.right + this.xoffset;
                    var b = grid._top + this.yoffset;
                    this._elem.css({right:a, top:b});
                    break;
                case 'e':
                    var a = offsets.right + this.xoffset;
                    var b = (offsets.top + (this._plotDimensions.height - offsets.bottom))/2 - this.getHeight()/2;
                    this._elem.css({right:a, top:b});
                    break;
                case 'se':
                    var a = offsets.right + this.xoffset;
                    var b = offsets.bottom + this.yoffset;
                    this._elem.css({right:a, bottom:b});
                    break;
                case 's':
                    var a = (offsets.left + (this._plotDimensions.width - offsets.right))/2 - this.getWidth()/2;
                    var b = offsets.bottom + this.yoffset;
                    this._elem.css({left:a, bottom:b});
                    break;
                case 'sw':
                    var a = grid._left + this.xoffset;
                    var b = offsets.bottom + this.yoffset;
                    this._elem.css({left:a, bottom:b});
                    break;
                case 'w':
                    var a = grid._left + this.xoffset;
                    var b = (offsets.top + (this._plotDimensions.height - offsets.bottom))/2 - this.getHeight()/2;
                    this._elem.css({left:a, top:b});
                    break;
                default:  // same as 'se'
                    var a = grid._right - this.xoffset;
                    var b = grid._bottom + this.yoffset;
                    this._elem.css({right:a, bottom:b});
                    break;
            }
        } 
    };
    	
    /**
     * JavaScript printf/sprintf functions.
     *
     * This code is unrestricted: you are free to use it however you like.
     * 
     * The functions should work as expected, performing left or right alignment,
     * truncating strings, outputting numbers with a required precision etc.
     *
     * For complex cases, these functions follow the Perl implementations of
     * (s)printf, allowing arguments to be passed out-of-order, and to set the
     * precision or length of the output based on arguments instead of fixed
     * numbers.
     *
     * See http://perldoc.perl.org/functions/sprintf.html for more information.
     *
     * Implemented:
     * - zero and space-padding
     * - right and left-alignment,
     * - base X prefix (binary, octal and hex)
     * - positive number prefix
     * - (minimum) width
     * - precision / truncation / maximum width
     * - out of order arguments
     *
     * Not implemented (yet):
     * - vector flag
     * - size (bytes, words, long-words etc.)
     * 
     * Will not implement:
     * - %n or %p (no pass-by-reference in JavaScript)
     *
     * @version 2007.04.27
     * @author Ash Searle
     */
     
     /**
      * @Modifications 2009.05.26
      * @author Chris Leonello
      * 
      * Added %p %P specifier
      * Acts like %g or %G but will not add more significant digits to the output than present in the input.
      * Example:
      * Format: '%.3p', Input: 0.012, Output: 0.012
      * Format: '%.3g', Input: 0.012, Output: 0.0120
      * Format: '%.4p', Input: 12.0, Output: 12.0
      * Format: '%.4g', Input: 12.0, Output: 12.00
      * Format: '%.4p', Input: 4.321e-5, Output: 4.321e-5
      * Format: '%.4g', Input: 4.321e-5, Output: 4.3210e-5
      */
    $.jqplot.sprintf = function() {
        function pad(str, len, chr, leftJustify) {
    	    var padding = (str.length >= len) ? '' : Array(1 + len - str.length >>> 0).join(chr);
        	return leftJustify ? str + padding : padding + str;

        }

        function justify(value, prefix, leftJustify, minWidth, zeroPad) {
    	    var diff = minWidth - value.length;
        	if (diff > 0) {
        	    if (leftJustify || !zeroPad) {
        		    value = pad(value, minWidth, ' ', leftJustify);
        	    } else {
        		    value = value.slice(0, prefix.length) + pad('', diff, '0', true) + value.slice(prefix.length);
        	    }
        	}
        	return value;
        }

        function formatBaseX(value, base, prefix, leftJustify, minWidth, precision, zeroPad) {
        	// Note: casts negative numbers to positive ones
        	var number = value >>> 0;
        	prefix = prefix && number && {'2': '0b', '8': '0', '16': '0x'}[base] || '';
        	value = prefix + pad(number.toString(base), precision || 0, '0', false);
        	return justify(value, prefix, leftJustify, minWidth, zeroPad);
        }

        function formatString(value, leftJustify, minWidth, precision, zeroPad) {
        	if (precision != null) {
        	    value = value.slice(0, precision);
        	}
        	return justify(value, '', leftJustify, minWidth, zeroPad);
        }

        var a = arguments, i = 0, format = a[i++];

        return format.replace($.jqplot.sprintf.regex, function(substring, valueIndex, flags, minWidth, _, precision, type) {
    	    if (substring == '%%') return '%';

    	    // parse flags
    	    var leftJustify = false, positivePrefix = '', zeroPad = false, prefixBaseX = false;
        	    for (var j = 0; flags && j < flags.length; j++) switch (flags.charAt(j)) {
        		case ' ': positivePrefix = ' '; break;
        		case '+': positivePrefix = '+'; break;
        		case '-': leftJustify = true; break;
        		case '0': zeroPad = true; break;
        		case '#': prefixBaseX = true; break;
    	    }

    	    // parameters may be null, undefined, empty-string or real valued
    	    // we want to ignore null, undefined and empty-string values

    	    if (!minWidth) {
    		    minWidth = 0;
    	    } 
    	    else if (minWidth == '*') {
    		    minWidth = +a[i++];
    	    } 
    	    else if (minWidth.charAt(0) == '*') {
    		    minWidth = +a[minWidth.slice(1, -1)];
    	    } 
    	    else {
    		    minWidth = +minWidth;
    	    }

    	    // Note: undocumented perl feature:
    	    if (minWidth < 0) {
        		minWidth = -minWidth;
        		leftJustify = true;
    	    }

    	    if (!isFinite(minWidth)) {
    		    throw new Error('$.jqplot.sprintf: (minimum-)width must be finite');
    	    }

    	    if (!precision) {
    		    precision = 'fFeE'.indexOf(type) > -1 ? 6 : (type == 'd') ? 0 : void(0);
    	    } 
    	    else if (precision == '*') {
    		    precision = +a[i++];
    	    } 
    	    else if (precision.charAt(0) == '*') {
    		    precision = +a[precision.slice(1, -1)];
    	    } 
    	    else {
    		    precision = +precision;
    	    }

    	    // grab value using valueIndex if required?
    	    var value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++];

    	    switch (type) {
    		case 's': return formatString(String(value), leftJustify, minWidth, precision, zeroPad);
    		case 'c': return formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad);
    		case 'b': return formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    		case 'o': return formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    		case 'x': return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    		case 'X': return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad).toUpperCase();
    		case 'u': return formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    		case 'i':
    		case 'd': {
              var number = parseInt(+value);
              var prefix = number < 0 ? '-' : positivePrefix;
              value = prefix + pad(String(Math.abs(number)), precision, '0', false);
              return justify(value, prefix, leftJustify, minWidth, zeroPad);
    			  }
    		case 'e':
    		case 'E':
    		case 'f':
    		case 'F':
    		case 'g':
    		case 'G':
    		          {
    			      var number = +value;
    			      var prefix = number < 0 ? '-' : positivePrefix;
    			      var method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())];
    			      var textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2];
    			      value = prefix + Math.abs(number)[method](precision);
    			      return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]();
    			  }
    		case 'p':
    		case 'P':
    		{
    		    // make sure number is a number
                var number = +value;
                var prefix = number < 0 ? '-' : positivePrefix;

                var parts = String(Number(Math.abs(number)).toExponential()).split(/e|E/);
                var sd = (parts[0].indexOf('.') != -1) ? parts[0].length - 1 : parts[0].length;
                var zeros = (parts[1] < 0) ? -parts[1] - 1 : 0
                
                if (Math.abs(number) < 1) {
                    if (sd + zeros  <= precision) {
                        value = prefix + Math.abs(number).toPrecision(sd);
                    }
                    else {
                        if (sd  <= precision - 1) {
                            value = prefix + Math.abs(number).toExponential(sd-1);
                        }
                        else {
                            value = prefix + Math.abs(number).toExponential(precision-1);
                        }
                    }
                }
                else {
                    var prec = (sd <= precision) ? sd : precision
                    value = prefix + Math.abs(number).toPrecision(prec);
                }
                var textTransform = ['toString', 'toUpperCase']['pP'.indexOf(type) % 2];
                return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]();
            }
    		default: return substring;
    	    }
    		    });
    }
    
    $.jqplot.sprintf.regex = /%%|%(\d+\$)?([-+#0 ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([scboxXuidfegpEGP])/g;

})(jQuery);  