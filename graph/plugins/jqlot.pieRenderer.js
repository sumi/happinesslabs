/**
 * Copyright (c) 2009 Chris Leonello
 * This software is licensed under the GPL version 2.0 and MIT licenses.
 */
(function($) {
    /**
     * Class: $.jqplot.PieRenderer
     * Plugin renderer to draw a pie chart.
     * Pie charts will draw only the first series.  Other series are ignored.
     * x values, if present, will be used as slice labels.
     * y values give slice size.
     * slice labels can also be supplied through a ticks array, and will
     * override the x values of the data series.
     */
    $.jqplot.PieRenderer = function(){
        $.jqplot.LineRenderer.call(this);
        // auto computed
        this.diameter;
        this.marginTop=20;
        this.marginLeft=20;
        this.shadow = false;
        this.fill = true;
        this.colors = [ "#4bb2c5", "#c5b47f", "#EAA228", "#579575", "#839557", "#958c12", "#953579", "#4b5de4", "#d8b83f", "#ff5800", "#0085cc"];
    };
    
    $.jqplot.PieRenderer.prototype = new $.jqplot.LineRenderer();
    $.jqplot.PieRenderer.prototype.constructor = $.jqplot.PieRenderer;
    
    // called with scope of a series
    $.jqplot.PieRenderer.init = function(options) {
        $.extend(true, this.renderer, options);
    }
    
    $.jqplot.PieAxisRenderer = function() {
        $.jqplot.linearAxisRenderer.call(this);
    };
    
    $.jqplot.PieAxisRenderer.prototype = new $.jqplot.linearAxisRenderer();
    $.jqplot.PieAxisRenderer.prototype.constructor = $.jqplot.PieAxisRenderer;
    
    
    // called with scope of axis object.
    $.jqplot.PieAxisRenderer.prototype.init = function(options){
        // prop: tickRenderer
        // A class of a rendering engine for creating the ticks labels displayed on the plot, 
        // See <$.jqplot.PieTickRenderer>.
        this.tickRenderer = $.jqplot.PieTickRenderer;
        $.extend(true, this, options);
        var db = this._dataBounds;
        // Go through all the series attached to this axis
        for (var i=0; i<this._series.length; i++) {
            var s = this._series[i];
            if (s.renderer.constructor == $.jqplot.PieRenderer) {
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
                // we're only going to render the first pie series we find
                break;
            }
        }
    };
    
    
    $.jqplot.PieTickRenderer = function() {
        $.jqplot.axisTickRenderer.call(this);
    };
    
    $.jqplot.PieTickRenderer.prototype = new $.jqplot.axisTickRenderer();
    $.jqplot.PieTickRenderer.prototype.constructor = $.jqplot.PieTickRenderer;
    
    $.jqplot.PieLegendRenderer = function() {
        $.jqplot.TableLegendRenderer.call(this);
    };
    
    $.jqplot.PieLegendRenderer.prototype = new $.jqplot.TableLegendRenderer();
    $.jqplot.PieLegendRenderer.prototype.constructor = $.jqplot.PieLegendRenderer;
    
    // setup default renderers for axes and legend so user doesn't have to
    // called with scope of plot
    function preInit(target, data, options) {
        options = options || {};
        options.axesDefaults = options.axesDefaults || {};
        options.legend = options.legend || {};
        options.axesDefaults.renderer = $.jqplot.PieAxisRenderer;
        options.legend.renderer = $.jqplot.PieLegendRenderer;
    }
    
    $.jqplot.preInitHooks.push(preInit);
    
    