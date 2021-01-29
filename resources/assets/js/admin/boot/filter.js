import Vue from "vue";

Vue.filter("elliptical", function(
    value,
    hideStr = "....",
    headLength = 6,
    endLength = 4
) {
    if (!value) return "";
    let val = value;

    if (val.length <= headLength + endLength) return val;
    return val.substr(0, headLength) + hideStr + val.substr(-endLength);
});

Vue.filter("floatNum", function(value, fixed = 2, tail = true) {
    const num = parseFloat(value || 0).toFixed(fixed);
    return tail ? num : parseFloat(num);
});
