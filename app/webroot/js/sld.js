/**
 * Created by DavidOyeku on 23/04/2016.
 */
function mapStyle(interq) {
    var layer = interq.layer;
    var propertyName = interq.column;
    var quartiles = interq.quartiles;
    console.log(layer);
    var sld =
        '<?xml version="1.0" encoding="UTF-8"?>' +
        '<StyledLayerDescriptor xmlns="http://www.opengis.net/sld" xmlns:ogc="http://www.opengis.net/ogc" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="1.1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://www.opengis.net/sld http://schemas.opengis.net/sld/1.1.0/StyledLayerDescriptor.xsd" xmlns:se="http://www.opengis.net/se">' +
        '<NamedLayer>' +
        '<se:Name>' + layer + '</se:Name>' +
        '<UserStyle>' +
        '<se:Name>' + layer + '</se:Name>' +
        '<se:FeatureTypeStyle>' +
        '<se:Rule>' +
        '<se:PolygonSymbolizer>' +
        '<se:Fill>' +
        '<se:SvgParameter name="fill">' +
        '<ogc:Function name="Interpolate">' +
        '<ogc:PropertyName>' + propertyName + '</ogc:PropertyName>' +
        '<ogc:Literal>' + quartiles[1] + '</ogc:Literal>' +
        '<ogc:Literal>#fee5d9</ogc:Literal>' +
        '<ogc:Literal>' + quartiles[2] + '</ogc:Literal>' +
        '<ogc:Literal>#fcae91</ogc:Literal>' +
        '<ogc:Literal>' + quartiles[3] + '</ogc:Literal> ' +
        '<ogc:Literal>#fb6a4a</ogc:Literal>' +
        '<ogc:Literal>' + quartiles[4] + '</ogc:Literal> ' +
        '<ogc:Literal>#de2d26</ogc:Literal>' +
        '<ogc:Literal>' + quartiles[5] + '</ogc:Literal> ' +
        '<ogc:Literal>#a50f15</ogc:Literal>' +
        '<ogc:Literal>color</ogc:Literal>' +
        '</ogc:Function>' +
        '</se:SvgParameter>' +
        '<se:SvgParameter name="fill-opacity">0.8</se:SvgParameter >' +
        '</se:Fill>' +
        '<se:Stroke>' +
        '<se:SvgParameter name="stroke">#FFFFFF</se:SvgParameter>' +
        '<se:SvgParameter name="stroke-width">0.5</se:SvgParameter>' +
        '</se:Stroke>' +
        '</se:PolygonSymbolizer>' +
        '</se:Rule>' +
        '</se:FeatureTypeStyle>' +
        '</UserStyle>' +
        '</NamedLayer>' +
        '</StyledLayerDescriptor>';
console.log(sld);
    return sld;
}